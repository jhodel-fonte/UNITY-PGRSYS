/* =====================================================
    USER MANAGEMENT LOGIC
===================================================== */


function createUserRowHtml(u, adminCurrentUser) {

    const uStatus = (u.status === 'NoOtpReg') ? 'Pending' : u.status;
    
    // Determine badge class
    let badgeClass = 'warning text-dark';
    if (uStatus === 'Active') {
        badgeClass = 'success';
    } else if (uStatus === 'Rejected') {
        badgeClass = 'danger';
    }


    let actionButtons = '';
    

    if (u.userId == adminCurrentUser) {
         actionButtons = '<span>Current Account</span>';
    } 

    else if (uStatus === 'Pending') {
        actionButtons = `
            <button class="btn btn-sm btn-success action-btn" data-action="approve" data-userid="${u.userId}">Approve</button>
            <button class="btn btn-sm btn-danger action-btn" data-action="reject" data-userid="${u.userId}">Reject</button>
        `;
    } 

    else if (uStatus === 'Rejected') {
        actionButtons = `
            <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="${u.userId}">Delete</button>
        `;
    } 

    else if (uStatus === 'Active') {
        actionButtons = `
            <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="${u.userId}">Delete</button>
        `;
    } 

    else {
         actionButtons = '<span>No Action</span>';
    }


    return `
        <tr>
            <td>${u.userId}</td>
            <td>${u.firstName} ${u.lastName}</td>
            <td>${u.mobileNum || 'N/A'}</td>
            <td>${u.email || 'N/A'}</td>
            <td>
                <span class="badge rounded-pill bg-${badgeClass}">${uStatus}</span>
            </td>
            <td>
                <button class="btn btn-sm btn-info"
                    data-bs-toggle="modal"
                    data-bs-target="#userModal${u.userId}">
                    View
                </button>
            </td>
            <td>${actionButtons}</td>
        </tr>
    `;
}


function sortUsers(users, sortBy) {
    const sortedUsers = [...users];

    sortedUsers.sort((a, b) => {
        let comparison = 0;

        if (sortBy === 'newest' || sortBy === 'oldest') {
            const dateA = new Date(a.dateCreated).getTime();
            const dateB = new Date(b.dateCreated).getTime();
            comparison = dateA - dateB;

            if (sortBy === 'newest') {
                comparison *= -1;
            }
        } else if (sortBy === 'name_asc' || sortBy === 'name_desc') {
            const nameA = `${a.lastName || ''} ${a.firstName || ''}`.toLowerCase();
            const nameB = `${b.lastName || ''} ${b.firstName || ''}`.toLowerCase();

            if (nameA < nameB) comparison = -1;
            else if (nameA > nameB) comparison = 1;

            if (sortBy === 'name_desc') {
                comparison *= -1;
            }
        }
        return comparison;
    });

    return sortedUsers;
}

// 3. Filtering and Rendering Function (main orchestrator)
function handleSortAndFilter() {
    const tableBody = document.getElementById('userTableBody');
    const searchInput = document.getElementById('userSearch');
    const sortSelect = document.getElementById('sortSelect');
    
    // Check for required elements and global data
    if (!tableBody || !searchInput || !sortSelect || typeof allUsers === 'undefined') {
        return;
    }

    const searchValue = searchInput.value.toLowerCase().trim();
    const sortBy = sortSelect.value;
    
    // Use initialFilterStatus from PHP script block to maintain URL filtering
    const currentURLStatus = typeof initialFilterStatus !== 'undefined' ? initialFilterStatus : 'All';
    // Use adminCurrentUser from PHP script block
    const adminUser = typeof adminCurrentUser !== 'undefined' ? adminCurrentUser : null;

    // 1. Apply Filtering (Search and Status)
    let filteredUsers = allUsers.filter(u => {
        const uStatus = (u.status === 'NoOtpReg') ? 'Pending' : u.status;
        
        // Filter by Status (set by PHP URL)
        if (currentURLStatus !== 'All' && uStatus !== currentURLStatus) {
            return false;
        }

        // Filter by Search input
        if (searchValue) {
            const searchTerms = `${u.userId} ${u.firstName} ${u.lastName} ${u.email} ${u.mobileNum}`.toLowerCase();
            return searchTerms.includes(searchValue);
        }

        return true;
    });

    // 2. Apply Sorting
    const finalUsers = sortUsers(filteredUsers, sortBy);

    // 3. Render Table
    let htmlContent = '';
    if (finalUsers.length === 0) {
        htmlContent = `<tr><td colspan="7">No users found matching the current criteria.</td></tr>`;
    } else {
        finalUsers.forEach(u => {
            htmlContent += createUserRowHtml(u, adminUser);
        });
    }
    
    tableBody.innerHTML = htmlContent;
}


// 4. AJAX/Fetch logic for handling action buttons (Approve/Reject/Delete)
async function handleUserAction(event) {
    const button = event.target.closest('.action-btn');
    if (!button) return;

    const userId = button.getAttribute('data-userid');
    const action = button.getAttribute('data-action');
    const actionText = action.charAt(0).toUpperCase() + action.slice(1);

    const result = await Swal.fire({
        title: `${actionText} User?`,
        text: `Are you sure you want to ${action} user ID ${userId}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'delete' ? '#d33' : '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: `Yes, ${actionText} it!`
    });

    if (result.isConfirmed) {
        Swal.fire({
            title: 'Processing...',
            text: 'Updating user status. Please wait.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const formData = new FormData();
            formData.append('userId', userId);
            formData.append('action', action);

            const response = await fetch(USER_ACTION_ENDPOINT, { 
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }

            const data = await response.json();
            Swal.close(); 

            if (data.success) {
                Swal.fire('Success!', data.message, 'success');
                
                // Update the client-side data model (allUsers array)
                const userIndex = allUsers.findIndex(u => u.userId == userId);
                if (userIndex !== -1) {
                    if (action !== 'delete') {
                        // Update status in the data model
                        allUsers[userIndex].status = data.newStatus;
                    } else {
                        // Remove user from the data model
                        allUsers.splice(userIndex, 1);
                    }
                }
                
                // Re-render the table with the updated data
                handleSortAndFilter(); 

            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        } catch (error) {
            Swal.close();
            console.error('Action Fetch Error:', error);
            Swal.fire('Connection Error!', `Could not perform action. Details: ${error.message}`, 'error');
        }
    }
}


// --- INITIALIZATION ---
document.addEventListener('DOMContentLoaded', function() {
    const userTableBody = document.getElementById('userTableBody');
    const searchInput = document.getElementById('userSearch');
    const sortSelect = document.getElementById('sortSelect');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', handleSortAndFilter);
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', handleSortAndFilter);
        
        sortSelect.value = sortSelect.value || 'newest';
    }

    document.addEventListener('click', handleUserAction);
    handleSortAndFilter(); 
});