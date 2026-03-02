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
function togglePassword(inputId, iconWrapper) {

    const input = document.getElementById(inputId);

    const eyeIcon = `
        <svg xmlns="http://www.w3.org/2000/svg"
             width="20" height="20" viewBox="0 0 24 24"
             fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round"
             stroke-linejoin="round">
            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7S2 12 2 12z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>
    `;

    const eyeOffIcon = `
        <svg xmlns="http://www.w3.org/2000/svg"
             width="20" height="20" viewBox="0 0 24 24"
             fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round"
             stroke-linejoin="round">
            <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/>
            <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/>
            <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/>
            <path d="m2 2 20 20"/>
        </svg>
    `;

    if (input.type === "password") {
        input.type = "text";
        iconWrapper.innerHTML = eyeIcon;   // show normal eye when visible
    } else {
        input.type = "password";
        iconWrapper.innerHTML = eyeOffIcon; // show eye-off when hidden
    }
}