
async function populateBarangayDropdown() {
    const selectElement = document.getElementById('barangay');
    const BARANGAY_ENDPOINT = '../../app/api/data/listAddress.json';
    selectElement.innerHTML = '<option value="">Select Barangay</option>';

    try {
        const response = await fetch(BARANGAY_ENDPOINT);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        // Assuming your PHP returns an array of barangays in a 'data' property
        const barangays = data.data || []; 

        barangays.forEach(barangay => {
            const option = document.createElement('option');
            
            option.value = barangay.id; 
            option.textContent = barangay.name; 
            selectElement.appendChild(option);
        });

    } catch (error) {
        console.error("Error fetching barangays:", error);
        // Display an error option if fetching fails
        const errorOption = document.createElement('option');
        errorOption.textContent = "Error loading lists";
        errorOption.disabled = true;
        selectElement.appendChild(errorOption);
    }
}

// Call the function to populate the dropdown when the page loads
document.addEventListener('DOMContentLoaded', populateBarangayDropdown);