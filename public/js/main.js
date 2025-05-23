// Main JavaScript file to initialize and handle application functionality

// DOM Ready event
document.addEventListener('DOMContentLoaded', function() {
  // Initialize sidebar toggle functionality
  initSidebar();
  
  // Initialize search functionality
  initSearch();
  
  // Initialize navigation event listeners
  initNavigation();
  
  // Initialize dashboard
  initDashboard();
});

// Initialize sidebar toggle functionality
function initSidebar() {
  const sidebarCollapse = document.getElementById('sidebarCollapse');
  const sidebar = document.getElementById('sidebar');
  
  if (sidebarCollapse && sidebar) {
    sidebarCollapse.addEventListener('click', function() {
      sidebar.classList.toggle('active');
      
      // For smaller screens, add overlay when sidebar is active
      if (window.innerWidth < 992) {
        if (sidebar.classList.contains('active')) {
          // Create overlay
          const overlay = document.createElement('div');
          overlay.className = 'sidebar-overlay';
          overlay.style.position = 'fixed';
          overlay.style.top = '0';
          overlay.style.left = '0';
          overlay.style.width = '100%';
          overlay.style.height = '100%';
          overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.4)';
          overlay.style.zIndex = '998';
          overlay.style.transition = 'opacity 0.3s ease';
          overlay.id = 'sidebarOverlay';
          
          // Add click event to close sidebar when overlay is clicked
          overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.remove();
          });
          
          document.body.appendChild(overlay);
        } else {
          // Remove overlay
          const overlay = document.getElementById('sidebarOverlay');
          if (overlay) {
            overlay.remove();
          }
        }
      }
    });
    
    // Close sidebar on window resize for larger screens
    window.addEventListener('resize', function() {
      if (window.innerWidth >= 992) {
        sidebar.classList.remove('active');
        const overlay = document.getElementById('sidebarOverlay');
        if (overlay) {
          overlay.remove();
        }
      }
    });
  }
}

// Initialize search functionality
function initSearch() {
  const searchInput = document.getElementById('search-input');
  
  if (searchInput) {
    searchInput.addEventListener('keyup', function(event) {
      if (event.key === 'Enter') {
        const searchTerm = searchInput.value.trim().toLowerCase();
        
        if (searchTerm.length >= 3) {
          // Perform search (simplified for demo)
          const results = mockRequests.filter(request => 
            request.id.toLowerCase().includes(searchTerm) || 
            request.citizen.name.toLowerCase().includes(searchTerm) ||
            request.documentType.toLowerCase().includes(searchTerm)
          );
          
          // Update UI with search results (simplified)
          console.log('Search results:', results);
          
          // For demonstration, show first result if found
          if (results.length > 0) {
            showRequestDetails(results[0].id);
            showNotification(`Recherche pour "${searchTerm}" : ${results.length} résultat(s) trouvé(s)`);
          } else {
            showNotification(`Aucun résultat trouvé pour "${searchTerm}"`, 'warning');
          }
        } else if (searchTerm.length > 0) {
          showNotification('Veuillez entrer au moins 3 caractères', 'warning');
        }
      }
    });
  }
}

// Initialize navigation event listeners
function initNavigation() {
  // Dashboard link
  const dashboardLink = document.getElementById('dashboard-link');
  if (dashboardLink) {
    dashboardLink.addEventListener('click', function(e) {
      e.preventDefault();
      loadDashboardView();
    });
  }
  
  // Pending requests link
  const pendingLink = document.getElementById('pending-link');
  if (pendingLink) {
    pendingLink.addEventListener('click', function(e) {
      e.preventDefault();
      loadPendingRequestsView();
    });
  }
  
  // Approved requests link
  const approvedLink = document.getElementById('approved-link');
  if (approvedLink) {
    approvedLink.addEventListener('click', function(e) {
      e.preventDefault();
      loadApprovedRequestsView();
    });
  }
  
  // Rejected requests link
  const rejectedLink = document.getElementById('rejected-link');
  if (rejectedLink) {
    rejectedLink.addEventListener('click', function(e) {
      e.preventDefault();
      loadRejectedRequestsView();
    });
  }
  
  // Settings link
  const settingsLink = document.getElementById('settings-link');
  if (settingsLink) {
    settingsLink.addEventListener('click', function(e) {
      e.preventDefault();
      showNotification('La page des paramètres n\'est pas encore implémentée', 'info');
    });
  }
  
  // Logout link
  const logoutLink = document.getElementById('logout-link');
  if (logoutLink) {
    logoutLink.addEventListener('click', function(e) {
      e.preventDefault();
      showNotification('Fonctionnalité de déconnexion non implémentée', 'info');
    });
  }
}

// Load pending requests view
function loadPendingRequestsView() {
  const mainContent = document.getElementById('main-content');
  
  if (!mainContent) return;
  
  // Create content
  mainContent.innerHTML = `
    <div class="row">
      <div class="col-12">
        <h1 class="page-title">Demandes en Attente</h1>
        <p class="text-muted">Liste des demandes à traiter</p>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Demandes en attente</h5>
            <div class="d-flex">
              <div class="dropdown me-2">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="typeFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  Type de document
                </button>
                <ul class="dropdown-menu" aria-labelledby="typeFilterDropdown">
                  <li><a class="dropdown-item" href="#">Tous les types</a></li>
                  <li><a class="dropdown-item" href="#">Extrait de naissance</a></li>
                  <li><a class="dropdown-item" href="#">Acte de mariage</a></li>
                  <li><a class="dropdown-item" href="#">Acte de décès</a></li>
                  <li><a class="dropdown-item" href="#">Certificat de vie</a></li>
                  <li><a class="dropdown-item" href="#">Certificat de non revenu</a></li>
                  <li><a class="dropdown-item" href="#">Certificat d'entretien</a></li>
                </ul>
              </div>
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  Trier par
                </button>
                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                  <li><a class="dropdown-item" href="#">Plus récent</a></li>
                  <li><a class="dropdown-item" href="#">Plus ancien</a></li>
                  <li><a class="dropdown-item" href="#">Prioritaire</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Citoyen</th>
                    <th scope="col">Type de document</th>
                    <th scope="col">Date de demande</th>
                    <th scope="col">Priorité</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody id="pending-requests-list">
                  <!-- Will be populated dynamically -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
  
  // Populate the table with pending requests
  const pendingRequests = getRequestsByStatus('pending');
  const tableBody = document.getElementById('pending-requests-list');
  
  if (tableBody) {
    pendingRequests.forEach(request => {
      const row = document.createElement('tr');
      
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
        <td>${request.urgent ? '<span class="badge bg-danger">Urgent</span>' : '-'}</td>
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
  
  // Set active navigation
  setActiveNav('pending-link');
}

// Load approved requests view
function loadApprovedRequestsView() {
  const mainContent = document.getElementById('main-content');
  
  if (!mainContent) return;
  
  // Create content (similar to pending requests)
  mainContent.innerHTML = `
    <div class="row">
      <div class="col-12">
        <h1 class="page-title">Demandes Approuvées</h1>
        <p class="text-muted">Liste des demandes qui ont été approuvées</p>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Demandes approuvées</h5>
            <div class="d-flex">
              <div class="dropdown me-2">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="typeFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  Type de document
                </button>
                <ul class="dropdown-menu" aria-labelledby="typeFilterDropdown">
                  <li><a class="dropdown-item" href="#">Tous les types</a></li>
                  <li><a class="dropdown-item" href="#">Extrait de naissance</a></li>
                  <li><a class="dropdown-item" href="#">Acte de mariage</a></li>
                  <li><a class="dropdown-item" href="#">Acte de décès</a></li>
                  <li><a class="dropdown-item" href="#">Certificat de vie</a></li>
                  <li><a class="dropdown-item" href="#">Certificat de non revenu</a></li>
                  <li><a class="dropdown-item" href="#">Certificat d'entretien</a></li>
                </ul>
              </div>
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  Trier par
                </button>
                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                  <li><a class="dropdown-item" href="#">Plus récent</a></li>
                  <li><a class="dropdown-item" href="#">Plus ancien</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Citoyen</th>
                    <th scope="col">Type de document</th>
                    <th scope="col">Date d'approbation</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody id="approved-requests-list">
                  <!-- Will be populated dynamically -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
  
  // Populate the table with approved requests
  const approvedRequests = getRequestsByStatus('approved');
  const tableBody = document.getElementById('approved-requests-list');
  
  if (tableBody) {
    approvedRequests.forEach(request => {
      const row = document.createElement('tr');
      
      // Get approval date from history
      const approvalEntry = request.history.find(entry => entry.action.includes('approuvée'));
      const approvalDate = approvalEntry ? approvalEntry.date.split(' ')[0] : 'N/A';
      
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
        <td>${approvalDate}</td>
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
  
  // Set active navigation
  setActiveNav('approved-link');
}

// Load rejected requests view
function loadRejectedRequestsView() {
  const mainContent = document.getElementById('main-content');
  
  if (!mainContent) return;
  
  // Create content (similar to approved requests)
  mainContent.innerHTML = `
    <div class="row">
      <div class="col-12">
        <h1 class="page-title">Demandes Rejetées</h1>
        <p class="text-muted">Liste des demandes qui ont été rejetées</p>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Demandes rejetées</h5>
            <div class="d-flex">
              <div class="dropdown me-2">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="typeFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  Type de document
                </button>
                <ul class="dropdown-menu" aria-labelledby="typeFilterDropdown">
                  <li><a class="dropdown-item" href="#">Tous les types</a></li>
                  <li><a class="dropdown-item" href="#">Extrait de naissance</a></li>
                  <li><a class="dropdown-item" href="#">Acte de mariage</a></li>
                  <li><a class="dropdown-item" href="#">Acte de décès</a></li>
                  <li><a class="dropdown-item" href="#">Certificat de vie</a></li>
                  <li><a class="dropdown-item" href="#">Certificat de non revenu</a></li>
                  <li><a class="dropdown-item" href="#">Certificat d'entretien</a></li>
                </ul>
              </div>
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  Trier par
                </button>
                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                  <li><a class="dropdown-item" href="#">Plus récent</a></li>
                  <li><a class="dropdown-item" href="#">Plus ancien</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Citoyen</th>
                    <th scope="col">Type de document</th>
                    <th scope="col">Date de rejet</th>
                    <th scope="col">Motif</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody id="rejected-requests-list">
                  <!-- Will be populated dynamically -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
  
  // Populate the table with rejected requests
  const rejectedRequests = getRequestsByStatus('rejected');
  const tableBody = document.getElementById('rejected-requests-list');
  
  if (tableBody) {
    rejectedRequests.forEach(request => {
      const row = document.createElement('tr');
      
      // Get rejection date and comment from history
      const rejectionEntry = request.history.find(entry => entry.action.includes('rejetée'));
      const rejectionDate = rejectionEntry ? rejectionEntry.date.split(' ')[0] : 'N/A';
      const rejectionComment = rejectionEntry ? rejectionEntry.comment : 'N/A';
      
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
        <td>${rejectionDate}</td>
        <td>
          <span class="d-inline-block text-truncate" style="max-width: 150px;" title="${rejectionComment}">
            ${rejectionComment}
          </span>
        </td>
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
  
  // Set active navigation
  setActiveNav('rejected-link');
}