document.addEventListener("DOMContentLoaded", function () {
    fetch('../../Backend/Chart_Data.php', {
        method: 'GET',
        credentials: 'include',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }

            if (Object.keys(data).length === 0) {
                // Handle case when there is no data
                console.log('No chart data available');
                return;
            }

            const labels = Object.keys(data);
            const values = Object.values(data);

            const ctx = document.getElementById('Pie_Chart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            'rgba(44, 96, 56, 1)',
                            'rgba(40, 167, 69, 1)'
                        ],
                        borderColor: [
                            'rgba(44, 96, 56, 1)',
                            'rgba(40, 167, 69, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
        });
    const notificaties = document.getElementById("notificaties");


    fetch("../../Backend/FetchMessages.php",
        {
        method: 'GET',
        credentials: 'include',
        headers: {
        'Content-Type': 'application/json'
        }
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Failed to fetch messages.");
            }
            return response.json();
            })
        .then((data) => {
            if (data.success) {
                renderNotifications(data.messages); 
            } else {
                console.error(data.error);
            }
        })
        .catch((error) => {
            console.error("Error fetching messages:", error);
        });


    // Render notifications
    function renderNotifications(messages) {
        notificaties.innerHTML = "";

        if (messages.length === 0) {
            notificaties.textContent = "Geen berichten beschikbaar.";
        }

        messages.forEach((message) => {
            const notifItem = document.createElement("div");
            notifItem.className = "notif-item";
            notifItem.textContent = message.Subject;

            notifItem.addEventListener("click", function () {
                openModal(message.Subject, message.Message);
            });

            notificaties.appendChild(notifItem);
        });
    }

    const modal = document.getElementById("modal");
    const modalTitle = document.getElementById("modal-title");
    const modalDescription = document.getElementById("modal-description");
    const closeBtn = document.getElementById("close-btn");

    function openModal(Subject, Message) {
        modal.style.display = "flex";
        modalTitle.textContent = Subject;
        modalDescription.textContent = Message;
    }

    closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
    fetchMessages();
});