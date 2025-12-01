const infoDisplay = document.getElementById("infoDisplay");
const editForm = document.getElementById("editInfoForm");
const passForm = document.getElementById("changePassForm");

document.getElementById("editInfoBtn").addEventListener("click", () => {
    infoDisplay.classList.add("d-none");
    passForm.classList.add("d-none");
    editForm.classList.remove("d-none");
});

document.getElementById("changePassBtn").addEventListener("click", () => {
    infoDisplay.classList.add("d-none");
    editForm.classList.add("d-none");
    passForm.classList.remove("d-none");
});

document.getElementById("cancelEditBtn").addEventListener("click", () => {
    editForm.classList.add("d-none");
    infoDisplay.classList.remove("d-none");
});

document.getElementById("cancelPassBtn").addEventListener("click", () => {
    passForm.classList.add("d-none");
    infoDisplay.classList.remove("d-none");
});
