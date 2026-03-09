// ==========================================
// ROOM DATA WITH DETAILED INFORMATION
// ==========================================
const roomData = {
    1: {
        name: 'Ocean View Suite',
        price: 450,
        badge: 'Popular',
        image: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800',
        highlights: [
            { icon: 'fa-bed', text: 'Premium King Bed with Egyptian Cotton' },
            { icon: 'fa-water', text: '180° Panoramic Ocean View' },
            { icon: 'fa-wine-glass', text: 'Complimentary Wine on Arrival' },
            { icon: 'fa-bath', text: 'Deep Soaking Tub & Rain Shower' }
        ],
        amenities: [
            'Free WiFi', 'Smart TV', 'Mini Bar', 'Coffee Machine', 
            'In-Room Safe', 'Climate Control', 'Daily Housekeeping', 'Room Service'
        ],
        privileges: [
            'Priority restaurant reservations',
            'Complimentary airport transfer',
            'Early check-in / Late check-out (subject to availability)',
            '10% discount on spa treatments',
            'Welcome fruit basket & chocolates'
        ]
    },
    2: {
        name: 'Garden Villa',
        price: 380,
        badge: 'New',
        image: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800',
        highlights: [
            { icon: 'fa-swimming-pool', text: 'Private Plunge Pool' },
            { icon: 'fa-leaf', text: 'Surrounded by Tropical Gardens' },
            { icon: 'fa-sun', text: 'Outdoor Rain Shower' },
            { icon: 'fa-couch', text: 'Private Terrace with Daybed' }
        ],
        amenities: [
            'Free WiFi', 'Smart TV', 'Mini Bar', 'Outdoor Dining',
            'Garden Access', 'Yoga Mat', 'In-Room Safe', 'Climate Control'
        ],
        privileges: [
            'Private garden breakfast setup',
            'Complimentary bicycle rental',
            'Guided garden tour',
            'Fresh flowers daily',
            'Priority spa booking'
        ]
    },
    3: {
        name: 'Beachfront Bungalow',
        price: 550,
        badge: 'Premium',
        image: 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800',
        highlights: [
            { icon: 'fa-umbrella-beach', text: 'Direct Beach Access' },
            { icon: 'fa-water', text: 'Uninterrupted Sea Views' },
            { icon: 'fa-cocktail', text: 'Private Beach Cabana' },
            { icon: 'fa-wind', text: 'Ocean Breeze Terrace' }
        ],
        amenities: [
            'Free WiFi', 'Smart TV', 'Premium Mini Bar', 'Beach Towels',
            'Snorkeling Gear', 'Sun Loungers', 'Outdoor Shower', 'Beach Umbrella'
        ],
        privileges: [
            'Reserved beachfront dining spot',
            'Complimentary water sports equipment',
            'Sunset cocktail service on beach',
            'Private beach bonfire setup (on request)',
            'Priority water activity bookings'
        ]
    },
    4: {
        name: 'Rainforest Retreat',
        price: 320,
        badge: 'Eco',
        image: 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?w=800',
        highlights: [
            { icon: 'fa-leaf', text: 'Glass Walls into Jungle' },
            { icon: 'fa-tree', text: 'Canopy Level Views' },
            { icon: 'fa-seedling', text: 'Eco-Friendly Design' },
            { icon: 'fa-spa', text: 'Natural Soundscape' }
        ],
        amenities: [
            'Free WiFi', 'Organic Toiletries', 'Recycled Wood Furnishings',
            'Mosquito Netting', 'Reading Nook', 'Yoga Deck', 'Nature Guides', 'Binoculars'
        ],
        privileges: [
            'Complimentary guided nature walk',
            'Tree planting certificate',
            'Organic breakfast basket',
            'Wildlife spotting guidebook',
            '10% off eco-tours'
        ]
    },
    5: {
        name: 'Presidential Pavilion',
        price: 850,
        badge: 'Luxury',
        image: 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800',
        highlights: [
            { icon: 'fa-crown', text: 'Dedicated Butler Service' },
            { icon: 'fa-water', text: 'Private Infinity Pool' },
            { icon: 'fa-utensils', text: 'Personal Chef Available' },
            { icon: 'fa-car', text: 'Private Vehicle & Driver' }
        ],
        amenities: [
            'Free WiFi', 'Multiple Smart TVs', 'Full Kitchen', 'Dining Room',
            'Private Gym', 'Steam Room', 'Wine Cellar', 'Helipad Access'
        ],
        privileges: [
            '24/7 butler & concierge service',
            'Complimentary champagne & caviar',
            'Private helicopter transfer',
            'Exclusive restaurant access',
            'Customized excursion planning',
            'Personal shopping service'
        ]
    },
    6: {
        name: 'Overwater Villa',
        price: 680,
        badge: 'Unique',
        image: 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800',
        highlights: [
            { icon: 'fa-water', text: 'Glass Floor Panels' },
            { icon: 'fa-fish', text: 'Direct Lagoon Access' },
            { icon: 'fa-anchor', text: 'Private Overwater Deck' },
            { icon: 'fa-moon', text: 'Stargazing Loungers' }
        ],
        amenities: [
            'Free WiFi', 'Underwater Lighting', 'Snorkeling Gear', 'Kayak',
            'Sun Deck', 'Outdoor Jacuzzi', 'Marine Life Guide', 'Waterproof Bluetooth Speaker'
        ],
        privileges: [
            'Complimentary snorkeling tour',
            'Sunset dolphin cruise',
            'Floating breakfast tray',
            'Underwater photography session',
            'Marine biologist meet & greet'
        ]
    }
};

// State Management
let currentUser = null;
let userEmail = null;
let reservations = [];
let currentRoomPrice = 450;
let currentRoomName = '';
let currentRoomId = null;

// ==========================================
// INITIALIZATION
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    // Set default dates
    document.getElementById('modalCheckIn').valueAsDate = today;
    document.getElementById('modalCheckOut').valueAsDate = tomorrow;
    document.getElementById('serviceDate').valueAsDate = today;

    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Close modals on outside click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    });

    // Calculate totals on date change
    document.getElementById('modalCheckIn').addEventListener('change', calculateTotal);
    document.getElementById('modalCheckOut').addEventListener('change', calculateTotal);

    // Check for saved login session
    checkPersistentLogin();
});

// ==========================================
// PERSISTENT LOGIN FUNCTIONS
// ==========================================
function checkPersistentLogin() {
    const savedUser = localStorage.getItem('auraBayUser');
    const savedEmail = localStorage.getItem('auraBayEmail');
    
    if (savedUser && savedEmail) {
        currentUser = savedUser;
        userEmail = savedEmail;
        updateUIForLoggedInUser();
        
        // Update user display in dashboard
        document.getElementById('userDisplayName').innerHTML = `${savedUser}<br>${savedEmail}`;
    }
}

function saveLoginSession(username, email) {
    localStorage.setItem('auraBayUser', username);
    localStorage.setItem('auraBayEmail', email);
}

function clearLoginSession() {
    localStorage.removeItem('auraBayUser');
    localStorage.removeItem('auraBayEmail');
}

// ==========================================
// NAVIGATION & UI FUNCTIONS
// ==========================================
function scrollToSection(id) {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

function openLoginModal() {
    if (currentUser) {
        toggleDashboard();
    } else {
        document.getElementById('loginModal').classList.add('active');
    }
}

// ==========================================
// LOGIN REQUIRED CHECK FOR ROOMS
// ==========================================
function handleRoomClick(roomName, price, roomId) {
    if (!currentUser) {
        // Store intended action for after login
        sessionStorage.setItem('pendingBooking', JSON.stringify({
            type: 'room',
            roomName: roomName,
            price: price,
            roomId: roomId
        }));
        
        // Show login modal first
        document.getElementById('loginModal').classList.add('active');
        
        // Show message that login is required
        setTimeout(() => {
            const loginError = document.getElementById('loginError');
            loginError.textContent = 'Please login to book a room';
            loginError.style.display = 'block';
        }, 100);
    } else {
        // User is logged in, open booking modal directly
        openBookingModal(roomName, price, roomId);
    }
}

// ==========================================
// LOGIN REQUIRED CHECK FOR SERVICES
// ==========================================
function handleServiceClick(serviceName) {
    if (!currentUser) {
        // Store intended action for after login
        sessionStorage.setItem('pendingBooking', JSON.stringify({
            type: 'service',
            serviceName: serviceName
        }));
        
        // Show login modal first
        document.getElementById('loginModal').classList.add('active');
        
        setTimeout(() => {
            const loginError = document.getElementById('loginError');
            loginError.textContent = 'Please login to book a service';
            loginError.style.display = 'block';
        }, 100);
    } else {
        // User is logged in, open service modal directly
        openServiceModal(serviceName);
    }
}

// ==========================================
// ENHANCED BOOKING MODAL
// ==========================================
function openBookingModal(roomName, price, roomId) {
    currentRoomName = roomName;
    currentRoomPrice = price;
    currentRoomId = roomId;
    
    const room = roomData[roomId];
    
    // Update header info
    document.getElementById('bookingRoomName').textContent = roomName;
    document.getElementById('bookingModalPrice').innerHTML = `$${price}<span>/night</span>`;
    document.getElementById('bookingModalBadge').textContent = room.badge;
    document.getElementById('bookingModalImage').style.backgroundImage = `url('${room.image}')`;
    
    // Populate highlights
    const highlightsContainer = document.getElementById('roomHighlights');
    highlightsContainer.innerHTML = room.highlights.map(h => `
        <div class="highlight-item">
            <i class="fas ${h.icon}"></i>
            <span>${h.text}</span>
        </div>
    `).join('');
    
    // Populate amenities
    const amenitiesContainer = document.getElementById('roomAmenities');
    amenitiesContainer.innerHTML = room.amenities.map(a => `
        <span class="amenity-tag">
            <i class="fas fa-check"></i> ${a}
        </span>
    `).join('');
    
    // Populate privileges
    const privilegesContainer = document.getElementById('roomPrivileges');
    privilegesContainer.innerHTML = room.privileges.map(p => `
        <li><i class="fas fa-star"></i> ${p}</li>
    `).join('');
    
    // Update pricing
    document.getElementById('nightRate').textContent = '$' + price;
    calculateTotal();
    
    document.getElementById('bookingModal').classList.add('active');
}

function openServiceModal(serviceName) {
    if (serviceName) {
        document.getElementById('serviceName').textContent = serviceName;
    }
    document.getElementById('serviceModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    document.querySelectorAll('.error-msg').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.success-msg').forEach(el => el.style.display = 'none');
}

// ==========================================
// AUTHENTICATION
// ==========================================
function handleLogin(e) {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('password').value;

    // Simple demo authentication
    if (username === 'user123' && password === 'password123') {
        currentUser = username;
        userEmail = email || 'user@example.com';
        
        // Save to localStorage for persistence
        saveLoginSession(currentUser, userEmail);
        
        // Update UI
        document.getElementById('loginSuccess').style.display = 'block';
        document.getElementById('loginError').style.display = 'none';
        
        // Update user display
        document.getElementById('userDisplayName').innerHTML = `${currentUser}<br>${userEmail}`;
        
        setTimeout(() => {
            closeModal('loginModal');
            updateUIForLoggedInUser();
            
            // Check if there was a pending booking before login
            const pendingBooking = sessionStorage.getItem('pendingBooking');
            if (pendingBooking) {
                const booking = JSON.parse(pendingBooking);
                sessionStorage.removeItem('pendingBooking');
                
                // Open the appropriate modal after login
                setTimeout(() => {
                    if (booking.type === 'room') {
                        openBookingModal(booking.roomName, booking.price, booking.roomId);
                    } else if (booking.type === 'service') {
                        openServiceModal(booking.serviceName);
                    }
                }, 500);
            }
        }, 1000);
    } else {
        document.getElementById('loginError').textContent = 'Invalid credentials. Try user123 / password123';
        document.getElementById('loginError').style.display = 'block';
    }
}

function updateUIForLoggedInUser() {
    document.getElementById('loginNavBtn').textContent = 'My Account';
    document.getElementById('loginNavBtn').onclick = toggleDashboard;
}

function toggleDashboard() {
    const dashboard = document.getElementById('userDashboard');
    dashboard.classList.toggle('active');
    if (dashboard.classList.contains('active')) {
        showReservations();
    }
}

function logout() {
    currentUser = null;
    userEmail = null;
    reservations = [];
    
    // Clear localStorage
    clearLoginSession();
    
    // Clear any pending bookings
    sessionStorage.removeItem('pendingBooking');
    
    document.getElementById('userDashboard').classList.remove('active');
    document.getElementById('loginNavBtn').textContent = 'Login';
    document.getElementById('loginNavBtn').onclick = openLoginModal;
    
    // Reset login form
    document.getElementById('username').value = '';
    document.getElementById('loginEmail').value = '';
    document.getElementById('password').value = '';
    
    alert('Logged out successfully');
}

// ==========================================
// BOOKING LOGIC
// ==========================================
function calculateTotal() {
    const checkIn = new Date(document.getElementById('modalCheckIn').value);
    const checkOut = new Date(document.getElementById('modalCheckOut').value);
    const diffTime = checkOut - checkIn;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays > 0) {
        document.getElementById('numNights').textContent = diffDays;
        const total = diffDays * currentRoomPrice;
        document.getElementById('totalEstimate').textContent = '$' + total;
    } else {
        document.getElementById('numNights').textContent = 1;
        document.getElementById('totalEstimate').textContent = '$' + currentRoomPrice;
    }
}

function handleRoomBooking(e) {
    e.preventDefault();
    
    if (!currentUser) {
        alert('Please login first to make a reservation.');
        closeModal('bookingModal');
        openLoginModal();
        return;
    }

    const checkIn = document.getElementById('modalCheckIn').value;
    const checkOut = document.getElementById('modalCheckOut').value;
    const guests = document.getElementById('modalGuests').value;
    
    const reservationId = Date.now();
    
    const reservation = {
        id: reservationId,
        type: 'Room',
        name: currentRoomName,
        date: `${checkIn} to ${checkOut}`,
        guests: guests,
        status: 'confirmed'
    };
    
    reservations.push(reservation);
    
    // Show success message
    document.getElementById('bookingSuccess').style.display = 'block';
    
    setTimeout(() => {
        closeModal('bookingModal');
        document.getElementById('userDashboard').classList.add('active');
        showReservations();
        
        // Show confirmation popup
        document.getElementById('confirmationText').textContent = 
            `Your reservation for ${currentRoomName} has been confirmed! We look forward to welcoming you to Aura Bay Resort.`;
        document.getElementById('confirmationOverlay').classList.add('active');
        document.getElementById('confirmationPopup').classList.add('active');
    }, 1500);
}

function handleServiceBooking(e) {
    e.preventDefault();
    
    if (!currentUser) {
        alert('Please login first to book a service.');
        closeModal('serviceModal');
        openLoginModal();
        return;
    }

    const serviceName = document.getElementById('serviceName').textContent;
    const date = document.getElementById('serviceDate').value;
    const time = document.getElementById('serviceTime').value;
    const people = document.getElementById('servicePeople').value;
    
    const reservationId = Date.now();
    
    const reservation = {
        id: reservationId,
        type: 'Service',
        name: serviceName,
        date: `${date} at ${time}`,
        guests: people,
        status: 'confirmed'
    };
    
    reservations.push(reservation);
    
    // Show success message
    document.getElementById('serviceSuccess').style.display = 'block';
    
    setTimeout(() => {
        closeModal('serviceModal');
        document.getElementById('userDashboard').classList.add('active');
        showReservations();
        
        // Show confirmation popup
        document.getElementById('confirmationText').textContent = 
            `Your booking for ${serviceName} has been confirmed! Get ready for an amazing experience at Aura Bay Resort.`;
        document.getElementById('confirmationOverlay').classList.add('active');
        document.getElementById('confirmationPopup').classList.add('active');
    }, 1500);
}

function closeConfirmation() {
    document.getElementById('confirmationOverlay').classList.remove('active');
    document.getElementById('confirmationPopup').classList.remove('active');
}

function showReservations() {
    const container = document.getElementById('bookingsContainer');
    const list = document.getElementById('reservationsList');
    
    if (reservations.length === 0) {
        container.innerHTML = '<p style="color: #666; font-style: italic;">No reservations yet. Start exploring our rooms and services!</p>';
    } else {
        container.innerHTML = reservations.map(r => `
            <div class="reservation-item ${r.status}">
                <span class="reservation-status status-${r.status}">${r.status}</span>
                <h4>${r.type}: ${r.name}</h4>
                <p>${r.guests} Guest(s)</p>
                <div class="date">${r.date}</div>
            </div>
        `).join('');
    }
    
    list.style.display = 'block';
}

function openProfile() {
    alert('Profile feature coming soon! This will include personal preferences, payment methods, and loyalty rewards.');
}