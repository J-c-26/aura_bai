const VALID_EMAIL = "admin@gmail.com";
const VALID_PASSWORD = "admin123";
let isLoggedIn = false;
let currentRoom = "";
let currentPrice = 0;
let currentRoomId = 0;

// Room Data with Full Details (Preserved from web1)
const roomData = {
    1: {
        name: "Ocean View Suite",
        price: 450,
        badge: "Popular",
        image: "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800",
        highlights: [
            { icon: "fa-bed", text: "King Size Bed" },
            { icon: "fa-expand", text: "65 m² Space" },
            { icon: "fa-water", text: "Ocean View" },
            { icon: "fa-wifi", text: "Free WiFi" }
        ],
        amenities: ["Air Conditioning", "Mini Bar", "Room Service", "Safe Box", "Coffee Machine", "Smart TV"],
        privileges: [
            "Complimentary breakfast for 2",
            "Access to private beach area",
            "Free airport transfer",
            "Welcome champagne bottle",
            "Late checkout (2 PM)"
        ]
    },
    2: {
        name: "Garden Villa",
        price: 380,
        badge: "New",
        image: "https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800",
        highlights: [
            { icon: "fa-bed", text: "Queen Bed" },
            { icon: "fa-expand", text: "80 m² Space" },
            { icon: "fa-swimming-pool", text: "Private Pool" },
            { icon: "fa-spa", text: "Outdoor Shower" }
        ],
        amenities: ["Private Pool", "Garden Access", "Outdoor Shower", "Mini Bar", "Room Service", "Smart TV"],
        privileges: [
            "Complimentary breakfast for 2",
            "Garden meditation session",
            "Free bicycle rental",
            "Welcome fruit basket",
            "Access to spa facilities"
        ]
    },
    3: {
        name: "Beachfront Bungalow",
        price: 550,
        badge: "Premium",
        image: "https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800",
        highlights: [
            { icon: "fa-bed", text: "King Size Bed" },
            { icon: "fa-expand", text: "95 m² Space" },
            { icon: "fa-umbrella-beach", text: "Beach Access" },
            { icon: "fa-cocktail", text: "Private Bar" }
        ],
        amenities: ["Direct Beach Access", "Private Terrace", "Outdoor Jacuzzi", "Mini Bar", "Butler Service", "Smart TV"],
        privileges: [
            "All-inclusive meal plan",
            "Private beach cabana",
            "Sunset cocktail service",
            "Complimentary water sports",
            "Priority restaurant reservations"
        ]
    },
    4: {
        name: "Rainforest Retreat",
        price: 320,
        badge: "Eco",
        image: "https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?w=800",
        highlights: [
            { icon: "fa-bed", text: "Queen Bed" },
            { icon: "fa-expand", text: "55 m² Space" },
            { icon: "fa-leaf", text: "Jungle View" },
            { icon: "fa-tree", text: "Eco-Friendly" }
        ],
        amenities: ["Glass Walls", "Rainforest View", "Organic Toiletries", "No Plastic Policy", "Natural Ventilation", "Smart TV"],
        privileges: [
            "Guided nature walk",
            "Tree planting certificate",
            "Organic breakfast",
            "Wildlife spotting guide",
            "Eco-tour discount"
        ]
    },
    5: {
        name: "Presidential Pavilion",
        price: 850,
        badge: "Luxury",
        image: "https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800",
        highlights: [
            { icon: "fa-bed", text: "2 King Beds" },
            { icon: "fa-expand", text: "200 m² Space" },
            { icon: "fa-concierge-bell", text: "Butler Service" },
            { icon: "fa-swimming-pool", text: "Infinity Pool" }
        ],
        amenities: ["Infinity Pool", "Private Butler", "Separate Living Area", "Kitchen", "Dining Room", "Home Theater"],
        privileges: [
            "24/7 personal butler",
            "Private chef available",
            "Helicopter transfer",
            "Exclusive lounge access",
            "Complimentary spa package"
        ]
    },
    6: {
        name: "Overwater Villa",
        price: 680,
        badge: "Unique",
        image: "https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800",
        highlights: [
            { icon: "fa-bed", text: "King Size Bed" },
            { icon: "fa-expand", text: "110 m² Space" },
            { icon: "fa-water", text: "Lagoon Access" },
            { icon: "fa-fish", text: "Glass Floor" }
        ],
        amenities: ["Glass Floor Panels", "Direct Water Access", "Private Deck", "Snorkeling Gear", "Sun Loungers", "Smart TV"],
        privileges: [
            "Sunrise breakfast delivery",
            "Private snorkeling guide",
            "Kayak rental included",
            "Underwater photography session",
            "Champagne sunset cruise"
        ]
    }
};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    checkLoginStatus();
    setupEventListeners();
    
    // Check if we're on history page
    if (document.getElementById('historyBody')) {
        updateHistoryPage();
    }
});

// Check Login Status from localStorage
function checkLoginStatus() {
    const savedLogin = localStorage.getItem('isLoggedIn');
    const savedEmail = localStorage.getItem('userEmail');
    
    if (savedLogin === 'true' && savedEmail) {
        isLoggedIn = true;
        document.getElementById('loginNavBtn').textContent = 'Logout';
        document.getElementById('userDisplayName').innerHTML = savedEmail + '<br>' + savedEmail;
        const historyNav = document.getElementById('historyNavItem');
        if (historyNav) historyNav.style.display = 'block';
    }
}

// Setup Event Listeners
function setupEventListeners() {
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('navbar');
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });

    // Set minimum date for date inputs
    const today = new Date().toISOString().split('T')[0];
    const checkInInput = document.getElementById('modalCheckIn');
    const checkOutInput = document.getElementById('modalCheckOut');
    const serviceDateInput = document.getElementById('serviceDate');
    
    if (checkInInput) checkInInput.min = today;
    if (checkOutInput) checkOutInput.min = today;
    if (serviceDateInput) serviceDateInput.min = today;
}

// Toggle Login/Logout
function toggleLogin() {
    if (isLoggedIn) {
        logout();
    } else {
        openModal('loginModal');
    }
}

// Handle Login
function handleLogin(e) {
    e.preventDefault();
    const email = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMsg = document.getElementById('loginError');
    const successMsg = document.getElementById('loginSuccess');

    if (email === VALID_EMAIL && password === VALID_PASSWORD) {
        isLoggedIn = true;
        localStorage.setItem('isLoggedIn', 'true');
        localStorage.setItem('userEmail', email);
        
        successMsg.style.display = 'block';
        errorMsg.style.display = 'none';
        
        setTimeout(() => {
            closeModal('loginModal');
            document.getElementById('loginNavBtn').textContent = 'Logout';
            document.getElementById('userDisplayName').innerHTML = email + '<br>' + email;
            const historyNav = document.getElementById('historyNavItem');
            if (historyNav) historyNav.style.display = 'block';
            
            // Clear form
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
            successMsg.style.display = 'none';
            
            // If on history page, reload to show content
            if (document.getElementById('historyBody')) {
                location.reload();
            }
        }, 1500);
    } else {
        errorMsg.style.display = 'block';
        successMsg.style.display = 'none';
    }
}

// Logout
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        isLoggedIn = false;
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('userEmail');
        
        document.getElementById('loginNavBtn').textContent = 'Login';
        const historyNav = document.getElementById('historyNavItem');
        if (historyNav) historyNav.style.display = 'none';
        
        // If on history page, reload to show login prompt
        if (document.getElementById('historyBody')) {
            location.reload();
        }
    }
}

// Handle Room Click - Check Login First
function handleRoomClick(roomName, price, roomId) {
    if (!isLoggedIn) {
        openModal('loginModal');
        return;
    }
    
    // Check if user has active booking
    checkActiveBooking().then(hasActive => {
        if (hasActive) {
            alert('You already have an active reservation. Please checkout first before booking a new room.');
            // Redirect to history page
            window.location.href = 'history.html';
        } else {
            openBookingModal(roomName, price, roomId);
        }
    });
}

// Check Active Booking Status
async function checkActiveBooking() {
    try {
        const response = await fetch('status_check.php');
        const data = await response.json();
        return data.currentStatus === "Pending" || data.currentStatus === "Confirmed" || data.currentStatus === "Staying";
    } catch (e) {
        console.error("Error checking active booking:", e);
        return false;
    }
}

// Open Booking Modal with Room Details (PRESERVED FROM WEB1)
function openBookingModal(roomName, price, roomId) {
    currentRoom = roomName;
    currentPrice = price;
    currentRoomId = roomId;
    
    const room = roomData[roomId];
    
    // Set modal content
    document.getElementById('bookingRoomName').textContent = room.name;
    document.getElementById('bookingModalBadge').textContent = room.badge;
    document.getElementById('bookingModalImage').style.backgroundImage = `url('${room.image}')`;
    document.getElementById('nightRate').textContent = '$' + room.price;
    document.getElementById('modalCheckIn').value = '';
    document.getElementById('modalCheckOut').value = '';
    document.getElementById('numNights').textContent = '0';
    document.getElementById('totalEstimate').textContent = '$0';
    document.getElementById('specialRequests').value = '';
    document.getElementById('bookingSuccess').style.display = 'none';
    
    // Populate highlights
    const highlightsContainer = document.getElementById('roomHighlights');
    highlightsContainer.innerHTML = room.highlights.map(h => `
        <div class="highlight-item" style="display: flex; align-items: center; gap: 12px; padding: 15px; background: white; border-radius: 12px; transition: all 0.3s;">
            <i class="fas ${h.icon}" style="font-size: 20px; color: var(--primary); width: 30px; text-align: center;"></i>
            <span style="font-weight: 500;">${h.text}</span>
        </div>
    `).join('');
    
    // Populate amenities
    const amenitiesContainer = document.getElementById('roomAmenities');
    amenitiesContainer.innerHTML = room.amenities.map(a => `
        <span class="amenity-tag" style="background: linear-gradient(135deg, var(--primary), var(--dark)); color: white; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-check" style="font-size: 12px;"></i> ${a}
        </span>
    `).join('');
    
    // Populate privileges
    const privilegesContainer = document.getElementById('roomPrivileges');
    privilegesContainer.innerHTML = room.privileges.map(p => `
        <li style="display: flex; align-items: flex-start; gap: 12px; color: #555; font-size: 15px; line-height: 1.5;">
            <i class="fas fa-star" style="color: var(--secondary); font-size: 18px; margin-top: 2px;"></i>
            <span>${p}</span>
        </li>
    `).join('');
    
    openModal('bookingModal');
}

// Calculate Total Price
function calculateTotal() {
    const checkIn = new Date(document.getElementById('modalCheckIn').value);
    const checkOut = new Date(document.getElementById('modalCheckOut').value);
    
    if (checkIn && checkOut && checkOut > checkIn) {
        const diffTime = Math.abs(checkOut - checkIn);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const total = diffDays * currentPrice;
        
        document.getElementById('numNights').textContent = diffDays;
        document.getElementById('totalEstimate').textContent = '$' + total.toLocaleString();
    } else {
        document.getElementById('numNights').textContent = '0';
        document.getElementById('totalEstimate').textContent = '$0';
    }
}

// Handle Room Booking Submit
async function handleRoomBooking(e) {
    e.preventDefault();
    
    const checkIn = document.getElementById('modalCheckIn').value;
    const checkOut = document.getElementById('modalCheckOut').value;
    const guests = document.getElementById('modalGuests').value;
    const requests = document.getElementById('specialRequests').value;
    
    if (!checkIn || !checkOut) {
        alert('Please select both check-in and check-out dates');
        return;
    }
    
    if (new Date(checkOut) <= new Date(checkIn)) {
        alert('Check-out date must be after check-in date');
        return;
    }
    
    const diffTime = Math.abs(new Date(checkOut) - new Date(checkIn));
    const nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    const totalPrice = nights * currentPrice;
    
    // Get user email from localStorage
    const userEmail = localStorage.getItem('userEmail') || 'guest@aurabay.com';
    
    // Show loading state
    const submitBtn = document.getElementById('confirmBookingBtn');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('reserve.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `room_name=${encodeURIComponent(currentRoom)}&price=$${encodeURIComponent(totalPrice)}&check_in=${encodeURIComponent(checkIn)}&check_out=${encodeURIComponent(checkOut)}&guests=${encodeURIComponent(guests)}&requests=${encodeURIComponent(requests)}&nights=${encodeURIComponent(nights)}&email=${encodeURIComponent(userEmail)}`
        });
        
        const data = await response.json();
        console.log('Booking response:', data);
        
        if (data.status === "Pending") {
            document.getElementById('bookingSuccess').style.display = 'block';
            
            // Show email warning if there was an issue
            if (data.error) {
                console.warn('Email warning:', data.error);
                document.getElementById('bookingSuccess').innerHTML = 
                    '<i class="fas fa-exclamation-triangle"></i> Booking saved but email failed. Check: ' + data.error;
            }
            
            setTimeout(() => {
                closeModal('bookingModal');
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
                showConfirmation('Booking request sent! Please check your email to confirm your reservation.');
            }, 2000);
        } else {
            alert('Error: ' + (data.message || 'Could not process booking'));
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    } catch (e) {
        console.error("Booking error:", e);
        alert('Server error. Please try again later.');
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    }
}

// Update History Page (for history.html)
async function updateHistoryPage() {
    if (!isLoggedIn) return;
    
    try {
        const response = await fetch('status_check.php');
        const data = await response.json();
        
        const historyBody = document.getElementById('historyBody');
        const activeCard = document.getElementById('activeBookingCard');
        
        // Build History Table
        if (data.history && data.history.length > 0) {
            historyBody.innerHTML = data.history.map(row => {
                let statusClass = '';
                let statusLabel = row.status;
                switch(row.status.toLowerCase()) {
                    case 'pending': statusClass = 'status-pending'; break;
                    case 'confirmed': statusClass = 'status-confirmed'; break;
                    case 'staying': statusClass = 'status-staying'; break;
                    case 'done': statusClass = 'status-done'; statusLabel = 'Completed'; break;
                    case 'cancelled': statusClass = 'status-cancelled'; break;
                    default: statusClass = 'status-pending';
                }
                
                return `
                    <tr>
                        <td style="padding: 15px 20px; border-bottom: 1px solid #eee;">
                            <strong style="color: var(--dark);">${row.room_name}</strong>
                        </td>
                        <td style="padding: 15px 20px; border-bottom: 1px solid #eee;">
                            <span class="${statusClass}" style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: white; background: ${getStatusColor(row.status)};">
                                ${statusLabel}
                            </span>
                        </td>
                        <td style="padding: 15px 20px; border-bottom: 1px solid #eee; color: #27ae60; font-weight: bold;">
                            ${row.price || 'N/A'}
                        </td>
                        <td style="padding: 15px 20px; border-bottom: 1px solid #eee; color: #666;">
                            ${row.check_in || '-'}
                        </td>
                        <td style="padding: 15px 20px; border-bottom: 1px solid #eee; color: #666;">
                            ${row.check_out || '-'}
                        </td>
                        <td style="padding: 15px 20px; border-bottom: 1px solid #eee; color: #888; font-size: 13px;">
                            ${row.created_at}
                        </td>
                    </tr>
                `;
            }).join('');
        } else {
            historyBody.innerHTML = '<tr><td colspan="6" style="padding: 30px; text-align: center; color: #999;">No booking history found.</td></tr>';
        }
        
        // Active Booking Visibility
        const isActive = (data.currentStatus === "Pending" || data.currentStatus === "Confirmed" || data.currentStatus === "Staying");
        if (isActive) {
            activeCard.style.display = "block";
            document.getElementById('activeDetails').innerHTML = `
                <strong style="color: var(--primary); font-size: 18px;">${data.roomName}</strong><br>
                <span style="color: #666; margin-top: 5px; display: inline-block;">
                    <i class="fas fa-info-circle" style="margin-right: 5px;"></i>
                    Status: <span style="color: var(--secondary); font-weight: 600;">${data.currentStatus}</span>
                </span>
            `;
        } else {
            activeCard.style.display = "none";
        }
    } catch (e) {
        console.error("History page update error:", e);
    }
}

function getStatusColor(status) {
    switch(status.toLowerCase()) {
        case 'pending': return '#ff9800';
        case 'confirmed': return '#4caf50';
        case 'staying': return '#2D5A4A';
        case 'done': return '#6c757d';
        case 'cancelled': return '#f44336';
        default: return '#ff9800';
    }
}

// Checkout Booking
async function checkoutBooking() {
    if (!confirm("Would you like to end your current stay? This will allow you to book again.")) {
        return;
    }
    
    try {
        const response = await fetch('checkout.php');
        const data = await response.json();
        
        showConfirmation('You have successfully checked out. Thank you for staying with us!');
        updateHistoryPage();
    } catch (e) {
        console.error("Checkout error:", e);
        alert('Error processing checkout. Please try again.');
    }
}

// Handle Service Click
function handleServiceClick(serviceName) {
    if (!isLoggedIn) {
        openModal('loginModal');
        return;
    }
    
    document.getElementById('serviceName').textContent = serviceName;
    openModal('serviceModal');
}

// Handle Service Booking
async function handleServiceBooking(e) {
    e.preventDefault();
    
    const date = document.getElementById('serviceDate').value;
    const time = document.getElementById('serviceTime').value;
    const people = document.getElementById('servicePeople').value;
    
    if (!date) {
        alert('Please select a date');
        return;
    }
    
    // Simulate service booking
    document.getElementById('serviceSuccess').style.display = 'block';
    
    setTimeout(() => {
        closeModal('serviceModal');
        document.getElementById('serviceSuccess').style.display = 'none';
        showConfirmation('Service booked successfully!');
    }, 1500);
}

// Modal Functions
function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    document.body.style.overflow = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Scroll to Section
function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

// Show Confirmation Popup
function showConfirmation(text) {
    document.getElementById('confirmationText').textContent = text;
    document.getElementById('confirmationPopup').classList.add('active');
    document.getElementById('confirmationOverlay').classList.add('active');
}

function closeConfirmation() {
    document.getElementById('confirmationPopup').classList.remove('active');
    document.getElementById('confirmationOverlay').classList.remove('active');
}

// Dashboard Menu Functions
function showReservations() {
    window.location.href = 'history.html';
    document.getElementById('userDashboard').classList.remove('active');
}

function openProfile() {
    alert('Profile feature coming soon!');
    document.getElementById('userDashboard').classList.remove('active');
}

function openServiceModal() {
    document.getElementById('userDashboard').classList.remove('active');
    scrollToSection('services');
}

// Toggle User Dashboard
document.addEventListener('DOMContentLoaded', function() {
    const loginNavBtn = document.getElementById('loginNavBtn');
    if (loginNavBtn) {
        loginNavBtn.addEventListener('click', function(e) {
            if (isLoggedIn) {
                const dashboard = document.getElementById('userDashboard');
                dashboard.classList.toggle('active');
            }
        });
    }
    
    // Close dashboard when clicking outside
    document.addEventListener('click', function(e) {
        const dashboard = document.getElementById('userDashboard');
        const loginBtn = document.getElementById('loginNavBtn');
        
        if (dashboard && loginBtn && !dashboard.contains(e.target) && !loginBtn.contains(e.target)) {
            dashboard.classList.remove('active');
        }
    });
});