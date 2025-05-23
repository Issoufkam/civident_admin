// Dashboard functionality

// Function to populate requests table
function populateRequestsTable(status = 'all') {
  const tableBody = document.querySelector('#requests-table tbody');
  
  if (!tableBody) return;
  
  // Clear existing rows
  tableBody.innerHTML = '';
  
  // Get requests based on status
  const requests = getRequestsByStatus(status);
  
  requests.slice(0, 5).forEach(request => {
    const row = document.createElement('tr');
    row.classList.add('fade-in');
    
    // Determine status class and label
    let statusClass = '';
    let statusLabel = '';
    
    switch (request.status) {
      case 'pending':
        statusClass = 'status-pending';
        statusLabel = 'En attente';
        break;
      case 'approved':
        statusClass = 'status-approved';
        statusLabel = 'Approuvé';
        break;
      case 'rejected':
        statusClass = 'status-rejected';
        statusLabel = 'Rejeté';
        break;
    }
    
    // Format date
    const requestDate = new Date(request.requestDate).toLocaleDateString('fr-FR');
    
    row.innerHTML = `
      <td>${request.id}</td>
      <td>
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 fw-medium">${request.citizen.name}</p>
            <p class="text-muted mb-0 small">${request.citizen.email}</p>
          </div>
        </div>
      </td>
      <td>${request.documentType}</td>
      <td>${requestDate}</td>
      <td><span class="badge status-badge ${statusClass}">${statusLabel}</span></td>
      <td>
        <button class="btn btn-sm btn-outline-primary view-request" data-request-id="${request.id}">
          <i class="bi bi-eye me-1"></i> Voir
        </button>
      </td>
    `;
    
    // Add event listener to view button
    row.querySelector('.view-request').addEventListener('click', function() {
      const requestId = this.getAttribute('data-request-id');
      showRequestDetails(requestId);
    });
    
    tableBody.appendChild(row);
  });
}

// Function to populate urgent requests list
function populateUrgentRequests() {
  const urgentRequestsList = document.querySelector('.urgent-requests');
  
  if (!urgentRequestsList) return;
  
  // Clear existing items
  urgentRequestsList.innerHTML = '';
  
  // Get urgent pending requests
  const urgentRequests = getUrgentRequests();
  
  if (urgentRequests.length === 0) {
    const emptyItem = document.createElement('li');
    emptyItem.className = 'list-group-item text-center text-muted';
    emptyItem.textContent = 'Aucune demande urgente en attente';
    urgentRequestsList.appendChild(emptyItem);
    return;
  }
  
  urgentRequests.forEach(request => {
    const listItem = document.createElement('li');
    listItem.className = 'list-group-item fade-in';
    
    // Format date
    const requestDate = new Date(request.requestDate).toLocaleDateString('fr-FR');
    
    listItem.innerHTML = `
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <p class="urgent-request-name mb-0">${request.citizen.name}</p>
          <p class="urgent-request-type mb-0">${request.documentType}</p>
          <small class="urgent-request-date text-muted">${requestDate}</small>
        </div>
        <div>
          <span class="badge bg-danger rounded-pill urgent-badge">Urgent</span>
        </div>
      </div>
      <div class="mt-2">
        <button class="btn btn-sm btn-outline-primary view-request" data-request-id="${request.id}">
          <i class="bi bi-eye me-1"></i> Voir la demande
        </button>
      </div>
    `;
    
    // Add event listener to view button
    listItem.querySelector('.view-request').addEventListener('click', function() {
      const requestId = this.getAttribute('data-request-id');
      showRequestDetails(requestId);
    });
    
    urgentRequestsList.appendChild(listItem);
  });
}

// Function to load dashboard view
function loadDashboardView() {
  const mainContent = document.getElementById('main-content');
  const dashboardTemplate = document.getElementById('dashboard-template');
  
  if (!mainContent || !dashboardTemplate) return;
  
  // Clone the dashboard template content
  const dashboardContent = dashboardTemplate.content.cloneNode(true);
  
  // Clear and append the new content
  mainContent.innerHTML = '';
  mainContent.appendChild(dashboardContent);
  
  // Initialize charts
  initCharts();
  
  // Populate requests table and urgent requests list
  populateRequestsTable();
  populateUrgentRequests();
  
  // Set active navigation
  setActiveNav('dashboard-link');
}

// Update sidebar active link
function setActiveNav(linkId) {
  // Remove active class from all links
  document.querySelectorAll('.sidebar .list-unstyled li').forEach(item => {
    item.classList.remove('active');
  });
  
  // Add active class to selected link
  const activeLink = document.getElementById(linkId);
  if (activeLink) {
    activeLink.parentElement.classList.add('active');
  }
}

// Initialize dashboard
function initDashboard() {
  // Load the dashboard view by default
  loadDashboardView();
}