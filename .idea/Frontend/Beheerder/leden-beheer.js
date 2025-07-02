document.addEventListener("DOMContentLoaded", function () {
    fetch('../../Backend/FetchUsers.php', {
        method: 'GET',
        credentials: 'include',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Failed to fetch users.");
            }
            return response.json();
        })
        .then((data) => {
            if (data.success) {
                renderUsers(data.Users);
            } else {
                console.error(data.error);
            }
        })
        .catch((error) => {
            console.error("Error fetching users:", error);
            const tableBody = document.querySelector("tbody");
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" style="color: #000000; text-align: center;">
                        Failed to load user data. Please try again later.
                    </td>
                </tr>
            `;
        });
});

function renderUsers(Users) {
    const tableBody = document.querySelector("tbody");
    tableBody.innerHTML = "";

    if (Users.length === 0) {
        const emptyRow = document.createElement("tr");
        const emptyCell = document.createElement("td");
        emptyCell.colSpan = 5;
        emptyCell.textContent = "Geen Users beschikbaar.";
        emptyRow.appendChild(emptyCell);
        tableBody.appendChild(emptyRow);
        return;
    }

    Users.forEach((User, index) => {
        const row = document.createElement("tr");
        row.id = `member${index + 1}`;

        row.setAttribute("data-phone", User.PhoneNumber || "Niet Beschikbaar");
        row.setAttribute("data-address", User.Address || "Niet Beschikbaar");
        row.setAttribute("data-zip", User.ZipCode || "Niet Beschikbaar");

        const nameCell = document.createElement("td");
        nameCell.id = `naam${index + 1}`;
        nameCell.textContent = User.Name;

        const complexCell = document.createElement("td");
        complexCell.id = `complex${index + 1}`;
        complexCell.textContent = User.Complex;

        const sizeCell = document.createElement("td");
        sizeCell.id = `grootte${index + 1}`;
        sizeCell.textContent = User.M;

        const emailCell = document.createElement("td");
        emailCell.id = `email${index + 1}`;
        emailCell.textContent = User.Email;

        const buttonCell = document.createElement("td");
        const infoButton = document.createElement("button");
        infoButton.className = "info-button";
        infoButton.textContent = "Meer Info";
        infoButton.setAttribute("onclick", `showInfo(${index + 1})`);
        buttonCell.appendChild(infoButton);

        row.appendChild(nameCell);
        row.appendChild(complexCell);
        row.appendChild(sizeCell);
        row.appendChild(emailCell);
        row.appendChild(buttonCell);

        tableBody.appendChild(row);
    });
}
// Modal functionality
const modal = document.getElementById("modal");
const closeBtn = document.getElementById("close-btn");

// Function to open the modal
function openModal() {
    modal.style.display = "flex";
}
closeBtn.addEventListener("click", closeModal);


function closeModal() {
    modal.style.display = "none";
}

function showInfo(index) {
    //Haalt de info van de gekozen gebruiker via de index
    const row = document.getElementById(`member${index}`);
    const name = document.getElementById(`naam${index}`).textContent;
    const complex = document.getElementById(`complex${index}`).textContent;
    const size = document.getElementById(`grootte${index}`).textContent;
    const email = document.getElementById(`email${index}`).textContent;
    const telefoon = row.getAttribute("data-phone");
    const address = row.getAttribute("data-address");
    const zipcode = row.getAttribute("data-zip");


    // Split de Naam in twee delen voor het te tonen.
    const nameParts = name.split(" ");
    const firstName = nameParts[0] || "";
    const lastName = nameParts.slice(1).join(" ") || "";
    // Split het adress in drie delen voor het te tonen.
    const addressParts = address.split(" ");
    const street = addressParts[0] || "";
    const houseNumber = addressParts.slice(1).join(" ") || "";

    // De info die in de Modal wordt gezet.
    document.getElementById("voornaam").value = firstName;
    document.getElementById("achternaam").value = lastName;
    document.getElementById("email").value = email;
    document.getElementById("telefoon").value = telefoon;
    document.getElementById("complex-naam").value = complex;
    document.getElementById("complex-size").value = size;
    document.getElementById("straat").value = street;
    document.getElementById("huisnummer").value = houseNumber;
    document.getElementById("postcode").value = zipcode;

    // Open the modal
    openModal();
}