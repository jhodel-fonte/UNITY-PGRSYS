(() => {
    const RESPONSE_TEAM_ENDPOINT = "../request/responseTeam.php";
    let responseTeamsCache = null;

    async function fetchResponseTeams() {
        if (responseTeamsCache !== null) {
            return responseTeamsCache;
        }

        const response = await fetch(RESPONSE_TEAM_ENDPOINT, {
            headers: { "Accept": "application/json" }
        });

        if (!response.ok) {
            throw new Error(`Unable to load response teams (status ${response.status}).`);
        }

        const payload = await response.json();
        if (!payload.success) {
            throw new Error(payload.message || "Failed to load response teams.");
        }

        responseTeamsCache = payload.data || [];
        return responseTeamsCache;
    }

    async function confirmActionWithTeamSelection(action, id) {
        const teams = await fetchResponseTeams();

        if (!teams.length) {
            await Swal.fire("No response teams", "Please add a response team before assigning.", "warning");
            throw new Error("No response teams available");
        }

        const teamOptions = {};
        teams.forEach(team => {
            const labelParts = [team.name || `Team ${team.team_id}`];
            if (team.contact_number) {
                labelParts.push(`(${team.contact_number})`);
            }
            teamOptions[team.team_id] = labelParts.join(" ");
        });

        const { value: selectedTeamId, isConfirmed } = await Swal.fire({
            title: "Assign Response Team",
            text: "Choose which response team will handle this report.",
            input: "select",
            inputPlaceholder: "Select a response team",
            inputOptions: teamOptions,
            showCancelButton: true,
            confirmButtonText: "Continue",
            cancelButtonText: "Cancel",
            inputValidator: (value) => {
                if (!value) {
                    return "Please choose a response team.";
                }
                return null;
            }
        });

        if (!isConfirmed || !selectedTeamId) {
            throw new Error("Team selection cancelled");
        }

        return selectedTeamId;
    }

    // Renamed and fixed the confusing confirmActionOverride function
    async function confirmAction(action, id) {
        const messages = {
            approve: "Approve and Assign this report?",
            reject: "Reject this report? (This action will delete it.)",
            delete: "Permanently delete this record?",
            edit: "Edit this record?"
        };

        try {
            let selectedTeamId = null;

            // 1. If action is 'approve', first get the team selection
            if (action === 'approve') {
                // This calls the SweetAlert prompt that returns the team ID
                selectedTeamId = await confirmActionWithTeamSelection(action, id); 
            }

            // 2. Show Final Confirmation (This replaces the duplicate/unawaited Swal.fire)
            const result = await Swal.fire({
                title: messages[action] || "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#198754",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, continue"
            });

            if (!result.isConfirmed) {
                return;
            }

            // 3. Perform Redirect/Action
            const url = new URL(window.location.href);
            url.searchParams.set("action", action);
            url.searchParams.set("id", id);
            if (selectedTeamId) {
                url.searchParams.set("team_id", selectedTeamId);
            }
            
             // Show a temporary loading screen before redirecting
             Swal.fire({
                title: 'Processing...',
                text: 'Redirecting to complete action. Please wait.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            window.location.href = url.toString();

        } catch (error) {
            // Handle cancellation from team selection or other errors
            if (error && error.message === "Team selection cancelled") {
                Swal.fire("Action Cancelled", "Response team assignment was cancelled.", "info");
                return;
            }
            console.error("Action failed:", error);
            Swal.fire("Error!", error.message || "An unexpected error occurred.", "error");
        }
    }
    
    // Make functions globally available
    window.confirmAction = confirmAction; 
    // FIX: Corrected typo in global assignment
    window.confirmActionWithTeamSelection = confirmActionWithTeamSelection;
    
})(); 


async function confirmTeamAction(action, id) {

    // For delete action, show confirmation dialog
    if (action === 'delete') {
        const result = await Swal.fire({
            title: "Permanently delete this team?",
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545", // Red for danger
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel"
        });

        if (result.isConfirmed) {
            
            Swal.fire({
                title: 'Processing...',
                text: 'Deleting team. Please wait.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            try {
                
                const response = await fetch("../app/controllers/teamAction.php", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded', 
                    },
                    // Send action and id to the controller
                    body: `action=${encodeURIComponent(action)}&id=${encodeURIComponent(id)}`
                });

                // Check for non-200 HTTP status
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();
                
                Swal.close(); // Close the processing dialog

                // 3. Handle Server Response
                if (data.success) {
                    // Success: Show message and reload the page
                    Swal.fire('Deleted!', data.message || 'Team successfully deleted.', 'success').then(() => {
                        location.reload(); 
                    });
                } else {
                    // Failure reported by the server (e.g., database error)
                    Swal.fire('Error!', data.message || 'Deletion failed due to an unknown server error.', 'error');
                }

            } catch (error) {
                // 4. Handle Network/Connection Error
                Swal.close();
                console.error('Delete Team Fetch Error:', error);
                Swal.fire('Connection Error!', `Could not delete team. Details: ${error.message}`, 'error');
            }
        }
        return;
    }

    // Default fallback for unknown actions
    console.warn(`Unknown team action: ${action}`);
}


const MEMBER_ACTION_ENDPOINT = "../app/controllers/add_members.php"; // Suggested new endpoint

function openAddMemberModal(teamId) {
    Swal.fire({
        title: "Add Team Member",
        html: `
            <input id="swal-member-search" class="swal2-input" placeholder="Search by name or email...">
            <div id="swal-member-results" class="mt-3"></div>
        `,
        showCancelButton: true,
        confirmButtonText: "Add Member",
        cancelButtonText: "Cancel",
        focusConfirm: false, 
        didOpen: () => {
            const searchInput = document.getElementById('swal-member-search');
            const resultsDiv = document.getElementById('swal-member-results');
            
            // TODO: Implement member search functionality
            searchInput.addEventListener('input', (e) => {
                // Search for available members to add
                console.log('Searching for members:', e.target.value);
            });
        },
        preConfirm: () => {
            // TODO: Get selected member ID from search results
            const selectedMember = null; 
            if (!selectedMember) {
                Swal.showValidationMessage('Please select a member');
                return false;
            }
            return selectedMember;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // TODO: Call API to add member to team
            console.log('Adding member to team:', teamId, result.value);
            Swal.fire("Success", "Member added to team (Simulated)", "success").then(() => {
                location.reload();
            });
        }
    });
}

function openEditMemberModal(memberId) {
    Swal.fire({
        title: "Edit Team Member",
        html: `
            <input id="swal-member-name" class="swal2-input" placeholder="Member Name">
            <input id="swal-member-contact" class="swal2-input" placeholder="Contact Number">
        `,
        showCancelButton: true,
        confirmButtonText: "Save Changes",
        cancelButtonText: "Cancel",
        didOpen: () => {
            // TODO: Load current member data (e.g., via Fetch API)
            console.log('Loading member data for:', memberId);
        },
        preConfirm: () => {
            const name = document.getElementById('swal-member-name').value;
            const contact = document.getElementById('swal-member-contact').value;
            
            if (!name || !contact) {
                Swal.showValidationMessage('Please fill in all fields');
                return false;
            }
            
            return { name, contact };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // TODO: Call API to update member
            console.log('Updating member:', memberId, result.value);
            Swal.fire("Success", "Member updated (Simulated)", "success").then(() => {
                location.reload();
            });
        }
    });
}

function removeMember(teamId, memberId) {
    Swal.fire({
        title: "Remove Member?",
        text: "Are you sure you want to remove this member from the team?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, remove",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            // TODO: Replace with proper API call (currently redirects)
            console.log('Removing member:', memberId, 'from team:', teamId);
            
            // Show processing screen before redirecting
            Swal.fire({
                title: 'Processing...',
                text: 'Removing member. Please wait.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // For now, redirect with action parameters
            const url = new URL(window.location.href);
            url.searchParams.set("action", "remove_member");
            url.searchParams.set("team_id", teamId);
            url.searchParams.set("member_id", memberId);
            window.location.href = url.toString();
        }
    });
}

// Make functions globally available
window.openAddMemberModal = openAddMemberModal;
window.openEditMemberModal = openEditMemberModal;
window.removeMember = removeMember;
window.confirmTeamAction = confirmTeamAction;