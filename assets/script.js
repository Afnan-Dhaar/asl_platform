function showToast(message,type){
    let toast = document.getElementById("toast");
    toast.innerHTML = message;
    toast.style.display = "block";
    toast.style.background = (type=="success") ? "green" : "red";

    setTimeout(()=>{ toast.style.display="none"; },3000);
}

// ===============================
// HELP MODAL FUNCTIONS
// ===============================
function openHelp(){
    document.getElementById("helpModal").classList.add("show");
}

function closeHelp(){
    document.getElementById("helpModal").classList.remove("show");
}
// ==========================================
// OPEN ASSET MODAL (AJAX FETCH SINGLE ASSET)
// ==========================================
function openAssetModal(id) {

    showLoader();

    fetch("ajax.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "action=get_asset&id=" + id
    })
    .then(response => response.json())
    .then(data => {

        document.getElementById("modalContent").innerHTML = `
            <h2>${data.name}</h2>
            <p><strong>Asset Code:</strong> ${data.asset_code}</p>
            <p><strong>Total Lands:</strong> ${data.total_lands}</p>
            <p><strong>Land Area:</strong> ${data.land_area} m²</p>
            <p><strong>Location:</strong> ${data.location}</p>
            <p><strong>City:</strong> ${data.city}</p>
            <p><strong>Status:</strong> ${data.status}</p>
            <p><strong>Valuation:</strong> SAR ${data.valuation}</p>
            <p><strong>Description:</strong> ${data.description}</p>
        `;

        document.getElementById("assetModal").classList.add("show");

        hideLoader();
    });
}

// CLOSE MODAL
function closeModal(){
    document.getElementById("assetModal").classList.remove("show");
}

// ==========================================
// APPLY FILTER (AJAX)
// ==========================================
function applyFilter(){

    let city = document.getElementById("filterCity").value;
    let status = document.getElementById("filterStatus").value;

    fetch("ajax.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "action=filter_assets&city=" + encodeURIComponent(city) +
      "&status=" + encodeURIComponent(status)
    })
    .then(response => response.text())
    .then(data => {

        // Replace cards without page reload
        document.getElementById("cardsContainer").innerHTML = data;
    });
}
// ==========================================
// LOAD ALL CARDS ON HOME PAGE (AJAX)
// ==========================================
function loadAllCards(){

    showLoader();

    fetch("ajax.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "action=filter_assets"
    })
    .then(response => response.text())
    .then(data => {

        document.getElementById("homeCardsContainer").innerHTML = data;
        document.querySelector(".view-btn").style.display = "none";

        hideLoader();
    });
}
function showLoader(){
    document.getElementById("globalLoader").style.display = "flex";
}

function hideLoader(){
    document.getElementById("globalLoader").style.display = "none";
}
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);

    if (input.type === "password") {
        input.type = "text";
        icon.textContent = "🙈";  // change icon when visible
    } else {
        input.type = "password";
        icon.textContent = "👁";
    }
}