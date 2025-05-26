@extends('layouts.app')


@section('content')
<style>
    :root {
      --primary: #0056b3;
      --primary-light: rgba(0, 86, 179, 0.1);
      --secondary: #6c757d;
      --success: #28a745;
      --success-light: rgba(40, 167, 69, 0.1);
      --warning: #ffc107;
      --danger: #dc3545;
      --light: #f8f9fa;
      --dark: #343a40;
      --gray-100: #f8f9fa;
      --gray-200: #e9ecef;
      --gray-300: #dee2e6;
      --gray-400: #ced4da;
      --gray-500: #adb5bd;
      --gray-600: #6c757d;
      --gray-700: #495057;
      --gray-800: #343a40;
      --gray-900: #212529;
      --transition-speed: 0.3s;
    }

    body {
      font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      background-color: var(--gray-100);
      color: var(--gray-800);
      line-height: 1.5;
    }
    /* Dashboard Cards */
    .stat-card {
      border-radius: 0.75rem;
      box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
      transition: transform var(--transition-speed);
      overflow: hidden;
      height: 100%;
    }

    .stat-card:hover {
      transform: translateY(-4px);
    }

    .card-indicator {
      width: 4px;
      height: 100%;
      position: absolute;
      left: 0;
      top: 0;
    }

    .card-body {
      position: relative;
    }

    /* Status Badges */
    .badge-pending {
      background-color: var(--warning);
      color: var(--dark);
    }

    .badge-approved {
      background-color: var(--success);
      color: white;
    }

    .badge-rejected {
      background-color: var(--danger);
      color: white;
    }

    /* Request Table */
    .table-requests th {
      font-weight: 600;
      color: var(--gray-700);
    }

    .table-requests tbody tr {
      transition: background-color var(--transition-speed);
      cursor: pointer;
    }

    .table-requests tbody tr:hover {
      background-color: var(--gray-200);
    }

    .table-requests .urgent {
      border-left: 3px solid var(--danger);
    }

    /* Detail View */
    .detail-view {
      background-color: white;
      border-radius: 0.75rem;
      box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .detail-section {
      padding: 1rem;
      border-bottom: 1px solid var(--gray-200);
    }

    .detail-section:last-child {
      border-bottom: none;
    }

    .timeline-item {
      position: relative;
      padding-left: 2rem;
      padding-bottom: 1.5rem;
    }

    .timeline-item::before {
      content: '';
      position: absolute;
      left: 0.5rem;
      top: 0;
      height: 100%;
      width: 2px;
      background-color: var(--gray-300);
    }

    .timeline-item:last-child::before {
      height: 1.25rem;
    }

    .timeline-badge {
      position: absolute;
      left: 0;
      top: 0;
      width: 1rem;
      height: 1rem;
      border-radius: 50%;
      background-color: var(--primary);
    }

    .document-item {
      border-radius: 0.5rem;
      background-color: var(--gray-100);
      padding: 0.75rem;
      margin-bottom: 0.5rem;
      transition: background-color var(--transition-speed);
    }

    .document-item:hover {
      background-color: var(--gray-200);
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .fade-in {
      animation: fadeIn var(--transition-speed);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
      .sidebar {
        min-height: auto;
      }
    }

    /* Chart Container */
    .chart-container {
      position: relative;
      height: 250px;
      width: 100%;
    }

    /* Search Bar */
    .search-container {
      position: relative;
    }

    .search-container .form-control {
      padding-left: 2.5rem;
    }

    .search-icon {
      position: absolute;
      left: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray-500);
    }

    /* Status Filter Buttons */
    .status-filter .btn {
      border-radius: 20px;
      padding: 0.375rem 1rem;
      margin-right: 0.5rem;
      transition: all var(--transition-speed);
    }
</style>

<div  class="container-fluid py-2 px-3 px-sm-4 px-lg-5" id="requestDetailSection">
          <div class="col-12">
            <div class="detail-view">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 id="detailTitle">Détail de la Demande</h4>
                <button class="btn btn-outline-secondary" id="closeDetailBtn">
                  <i class="bi bi-x-lg"></i> Fermer
                </button>
              </div>
              
              <div class="row">
                <!-- Left Column - Citizen Info & Request Details -->
                <div class="col-lg-6">
                  <div class="detail-section">
                    <h5><i class="bi bi-person me-2"></i>Informations du Citoyen</h5>
                    <div class="row mt-3">
                      <div class="col-sm-6 mb-2">
                        <p class="mb-1 text-muted">Nom</p>
                        <p class="fw-bold" id="citizenName">-</p>
                      </div>
                      <div class="col-sm-6 mb-2">
                        <p class="mb-1 text-muted">ID</p>
                        <p class="fw-bold" id="citizenId">-</p>
                      </div>
                      <div class="col-sm-6 mb-2">
                        <p class="mb-1 text-muted">Email</p>
                        <p class="fw-bold" id="citizenEmail">-</p>
                      </div>
                      <div class="col-sm-6 mb-2">
                        <p class="mb-1 text-muted">Téléphone</p>
                        <p class="fw-bold" id="citizenPhone">-</p>
                      </div>
                      <div class="col-12 mb-2">
                        <p class="mb-1 text-muted">Adresse</p>
                        <p class="fw-bold" id="citizenAddress">-</p>
                      </div>
                      <div class="col-sm-6 mb-2">
                        <p class="mb-1 text-muted">Date de Naissance</p>
                        <p class="fw-bold" id="citizenDob">-</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="detail-section">
                    <h5><i class="bi bi-file-earmark-text me-2"></i>Détails de la Demande</h5>
                    <div class="row mt-3">
                      <div class="col-sm-6 mb-2">
                        <p class="mb-1 text-muted">ID de la Demande</p>
                        <p class="fw-bold" id="requestId">-</p>
                      </div>
                      <div class="col-sm-6 mb-2">
                        <p class="mb-1 text-muted">Type de Document</p>
                        <p class="fw-bold" id="documentType">-</p>
                      </div>
                      <div class="col-sm-6 mb-2">
                        <p class="mb-1 text-muted">Date de Demande</p>
                        <p class="fw-bold" id="requestDate">-</p>
                      </div>
                      <div class="col-sm-6 mb-2">
                        <p class="mb-1 text-muted">Statut</p>
                        <p id="requestStatus">-</p>
                      </div>
                      <div class="col-sm-6 mb-2">
                        <p class="mb-1 text-muted">Urgent</p>
                        <p class="fw-bold" id="requestUrgent">-</p>
                      </div>
                    </div>
                    
                    <div class="mt-3">
                      <p class="mb-1 text-muted">Notes</p>
                      <div class="form-floating">
                        <textarea class="form-control" id="requestNotes" style="height: 100px"></textarea>
                        <label for="requestNotes">Notes sur la demande</label>
                      </div>
                      <button class="btn btn-sm btn-outline-primary mt-2" id="saveNotesBtn">
                        <i class="bi bi-save"></i> Enregistrer les notes
                      </button>
                    </div>
                  </div>
                </div>
                
                <!-- Right Column - Documents & History -->
                <div class="col-lg-6">
                  <div class="detail-section">
                    <h5><i class="bi bi-files me-2"></i>Documents Fournis</h5>
                    <div class="mt-3" id="documentsList">
                      <!-- Documents will be dynamically added here -->
                    </div>
                  </div>
                  
                  <div class="detail-section">
                    <h5><i class="bi bi-clock-history me-2"></i>Historique</h5>
                    <div class="mt-3">
                      <div class="timeline" id="requestTimeline">
                        <!-- Timeline items will be dynamically added here -->
                      </div>
                    </div>
                  </div>
                  
                  <div class="detail-section">
                    <h5><i class="bi bi-gear me-2"></i>Actions</h5>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                      <button class="btn btn-success" id="approveBtn">
                        <i class="bi bi-check-circle"></i> Approuver
                      </button>
                      <button class="btn btn-danger" id="rejectBtn">
                        <i class="bi bi-x-circle"></i> Rejeter
                      </button>
                      <button class="btn btn-outline-primary">
                        <i class="bi bi-printer"></i> Imprimer
                      </button>
                      <button class="btn btn-outline-secondary">
                        <i class="bi bi-envelope"></i> Envoyer Email
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="decisionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="decisionModalTitle">Approuver la demande</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="decisionComment" class="form-label">Commentaire</label>
            <textarea class="form-control" id="decisionComment" rows="3" placeholder="Ajoutez un commentaire pour cette décision..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" class="btn" id="confirmDecisionBtn">Confirmer</button>
        </div>
      </div>
    </div>
  </div>

<script>
    // Mock data for the dashboard
    const mockRequests = [
      {
        id: "REQ-2023-1254",
        citizen: {
          id: "CIT-2345",
          name: "Thomas Dubois",
          email: "thomas.dubois@example.com",
          phone: "06 12 34 56 78",
          address: "15 Rue des Lilas, 75020 Paris",
          dateOfBirth: "1985-04-12"
        },
        documentType: " de naissance",
        requestDate: "2023-05-15",
        status: "pending",
        urgent: true,
        notes: "Le demandeur a besoin du document rapidement pour une démarche administrative.",
        documents: [
          {
            name: "Pièce d'identité",
            type: "image/jpeg",
            size: "1.2 MB",
            uploadDate: "2023-05-15"
          },
          {
            name: "Justificatif de domicile",
            type: "application/pdf",
            size: "0.8 MB",
            uploadDate: "2023-05-15"
          }
        ],
        history: [
          {
            date: "2023-05-15 10:23",
            action: "Soumission de la demande",
            agent: "-",
            comment: "Demande créée par le citoyen"
          },
          {
            date: "2023-05-15 14:45",
            action: "Documents vérifiés",
            agent: "Marie Laurent",
            comment: "Documents conformes"
          }
        ]
      
          
    };

    // Helper functions
    function getRequestById(id) {
      return mockRequests.find(request => request.id === id);
    }

    function getUrgentRequests() {
      return mockRequests.filter(request => request.urgent && request.status === 'pending');
    }

    function getRequestsByStatus(status) {
      if (status === 'all') {
        return mockRequests;
      }
      return mockRequests.filter(request => request.status === status);
    }

    function updateRequestStatus(id, newStatus, comment, agent) {
      const request = getRequestById(id);
      if (request) {
        request.status = newStatus;
        
        const historyEntry = {
          date: new Date().toLocaleString('fr-FR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
          }),
          action: newStatus === 'approved' ? 'Demande approuvée' : 'Demande rejetée',
          agent: agent,
          comment: comment
        };
        
        request.history.push(historyEntry);
        return true;
      }
      return false;
    }

    function addNoteToRequest(id, note, agent) {
      const request = getRequestById(id);
      if (request) {
        request.notes = note;
        return true;
      }
      return false;
    }

    // DOM Manipulation Functions
    function populateRequestsTable(requests) {
      const tableBody = document.getElementById('requestsTableBody');
      tableBody.innerHTML = '';

      requests.forEach(request => {
        const row = document.createElement('tr');
        if (request.urgent) {
          row.classList.add('urgent');
        }
        row.dataset.requestId = request.id;

        let statusBadge = '';
        switch(request.status) {
          case 'pending':
            statusBadge = '<span class="badge badge-pending">En attente</span>';
            break;
          case 'approved':
            statusBadge = '<span class="badge badge-approved">Approuvée</span>';
            break;
          case 'rejected':
            statusBadge = '<span class="badge badge-rejected">Rejetée</span>';
            break;
        }

        row.innerHTML = `
          <td>${request.id}</td>
          <td>${request.citizen.name}</td>
          <td>${request.documentType}</td>
          <td>${request.requestDate}</td>
          <td>${statusBadge}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary view-btn" data-request-id="${request.id}">
              <i class="bi bi-eye"></i>
            </button>
          </td>
        `;

        tableBody.appendChild(row);
      });

      // Add event listeners to view buttons
      document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const requestId = e.currentTarget.dataset.requestId;
          showRequestDetails(requestId);
        });
      });

      // Add event listeners to table rows
      document.querySelectorAll('#requestsTableBody tr').forEach(row => {
        row.addEventListener('click', (e) => {
          if (!e.target.closest('.btn')) {
            const requestId = row.dataset.requestId;
            showRequestDetails(requestId);
          }
        });
      });
    }

    function showRequestDetails(requestId) {
      const request = getRequestById(requestId);
      if (!request) return;

      // Show the detail section
      document.getElementById('requestDetailSection').classList.remove('d-none');

      // Populate citizen information
      document.getElementById('citizenName').textContent = request.citizen.name;
      document.getElementById('citizenId').textContent = request.citizen.id;
      document.getElementById('citizenEmail').textContent = request.citizen.email;
      document.getElementById('citizenPhone').textContent = request.citizen.phone;
      document.getElementById('citizenAddress').textContent = request.citizen.address;
      document.getElementById('citizenDob').textContent = request.citizen.dateOfBirth;

      // Populate request details
      document.getElementById('requestId').textContent = request.id;
      document.getElementById('documentType').textContent = request.documentType;
      document.getElementById('requestDate').textContent = request.requestDate;
      
      const statusEl = document.getElementById('requestStatus');
      statusEl.innerHTML = '';
      let statusBadge = document.createElement('span');
      statusBadge.classList.add('badge');
      
      switch(request.status) {
        case 'pending':
          statusBadge.classList.add('badge-pending');
          statusBadge.textContent = 'En attente';
          break;
        case 'approved':
          statusBadge.classList.add('badge-approved');
          statusBadge.textContent = 'Approuvée';
          break;
        case 'rejected':
          statusBadge.classList.add('badge-rejected');
          statusBadge.textContent = 'Rejetée';
          break;
      }
      
      statusEl.appendChild(statusBadge);
      
      document.getElementById('requestUrgent').textContent = request.urgent ? 'Oui' : 'Non';
      document.getElementById('requestNotes').value = request.notes;

      // Populate documents
      const documentsListEl = document.getElementById('documentsList');
      documentsListEl.innerHTML = '';
      
      if (request.documents.length === 0) {
        documentsListEl.innerHTML = '<p class="text-muted">Aucun document fourni</p>';
      } else {
        request.documents.forEach(doc => {
          const docItem = document.createElement('div');
          docItem.classList.add('document-item');
          
          let iconClass = 'bi-file-earmark';
          if (doc.type.includes('pdf')) {
            iconClass = 'bi-file-earmark-pdf';
          } else if (doc.type.includes('image')) {
            iconClass = 'bi-file-earmark-image';
          }
          
          docItem.innerHTML = `
            <div class="d-flex align-items-center">
              <i class="bi ${iconClass} me-2 fs-5"></i>
              <div>
                <div class="fw-bold">${doc.name}</div>
                <div class="text-muted small">${doc.type} · ${doc.size} · Téléversé le ${doc.uploadDate}</div>
              </div>
              <button class="btn btn-sm btn-outline-primary ms-auto">
                <i class="bi bi-download"></i>
              </button>
            </div>
          `;
          
          documentsListEl.appendChild(docItem);
        });
      }

      // Populate timeline
      const timelineEl = document.getElementById('requestTimeline');
      timelineEl.innerHTML = '';
      
      request.history.forEach((item, index) => {
        const timelineItem = document.createElement('div');
        timelineItem.classList.add('timeline-item');
        
        timelineItem.innerHTML = `
          <div class="timeline-badge"></div>
          <div class="mb-1">
            <span class="fw-bold">${item.action}</span>
            <span class="text-muted small ms-2">${item.date}</span>
          </div>
          <p class="mb-1">Agent: ${item.agent}</p>
          <p class="text-muted small mb-0">${item.comment}</p>
        `;
        
        timelineEl.appendChild(timelineItem);
      });

      // Set up action buttons based on current status
      const approveBtn = document.getElementById('approveBtn');
      const rejectBtn = document.getElementById('rejectBtn');
      
      if (request.status === 'pending') {
        approveBtn.disabled = false;
        rejectBtn.disabled = false;
      } else {
        approveBtn.disabled = true;
        rejectBtn.disabled = true;
      }

      // Set up event listeners for the detail view
      document.getElementById('closeDetailBtn').addEventListener('click', () => {
        document.getElementById('requestDetailSection').classList.add('d-none');
      });

      document.getElementById('saveNotesBtn').addEventListener('click', () => {
        const newNote = document.getElementById('requestNotes').value;
        if (addNoteToRequest(request.id, newNote, 'Admin')) {
          alert('Notes enregistrées avec succès !');
        }
      });

      // Set up approval/rejection action
      approveBtn.onclick = () => showDecisionModal('approve', request.id);
      rejectBtn.onclick = () => showDecisionModal('reject', request.id);

      // Scroll to the detail section
      document.getElementById('requestDetailSection').scrollIntoView({ behavior: 'smooth' });
    }

    function showDecisionModal(action, requestId) {
      const modal = document.getElementById('decisionModal');
      const modalTitle = document.getElementById('decisionModalTitle');
      const confirmBtn = document.getElementById('confirmDecisionBtn');
      
      if (action === 'approve') {
        modalTitle.textContent = 'Approuver la demande';
        confirmBtn.classList.remove('btn-danger');
        confirmBtn.classList.add('btn-success');
      } else {
        modalTitle.textContent = 'Rejeter la demande';
        confirmBtn.classList.remove('btn-success');
        confirmBtn.classList.add('btn-danger');
      }
      
      const decisionModal = new bootstrap.Modal(modal);
      decisionModal.show();
      
      confirmBtn.onclick = () => {
        const comment = document.getElementById('decisionComment').value;
        const newStatus = action === 'approve' ? 'approved' : 'rejected';
        
        if (updateRequestStatus(requestId, newStatus, comment, 'Admin')) {
          decisionModal.hide();
          // Refresh the detail view
          showRequestDetails(requestId);
          // Refresh the table
          populateRequestsTable(getRequestsByStatus('all'));
          // Update the stats
          updateStats();
        }
      };
    }

    function updateStats() {
      // Update the statistics cards
      document.getElementById('total-requests').textContent = statsData.totalRequests;
      document.getElementById('pending-requests').textContent = statsData.pendingRequests;
      document.getElementById('approved-requests').textContent = statsData.approvedRequests;
      document.getElementById('rejected-requests').textContent = statsData.rejectedRequests;
    }

    function setupCharts() {
      // Activity Chart
      const activityCtx = document.getElementById('activityChart').getContext('2d');
      new Chart(activityCtx, {
        type: 'line',
        data: {
          labels: monthlyActivityData.labels,
          datasets: monthlyActivityData.datasets
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      // Document Types Chart
      const documentTypesCtx = document.getElementById('documentTypesChart').getContext('2d');
      new Chart(documentTypesCtx, {
        type: 'doughnut',
        data: {
          labels: documentTypeData.labels,
          datasets: [{
            data: documentTypeData.data,
            backgroundColor: [
              '#0056b3',
              '#4d94ff',
              '#99c2ff',
              '#28a745',
              '#5cd675',
              '#90e3a5'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'right',
              labels: {
                boxWidth: 12
              }
            }
          }
        }
      });
    }

    function setupEventListeners() {
      // Search functionality
      document.getElementById('searchInput').addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const filteredRequests = mockRequests.filter(request => 
          request.id.toLowerCase().includes(searchTerm) ||
          request.citizen.name.toLowerCase().includes(searchTerm) ||
          request.documentType.toLowerCase().includes(searchTerm)
        );
        populateRequestsTable(filteredRequests);
      });

      // Status filter buttons
      document.querySelectorAll('.status-filter button').forEach(btn => {
        btn.addEventListener('click', (e) => {
          // Remove active class from all buttons
          document.querySelectorAll('.status-filter button').forEach(b => {
            b.classList.remove('btn-primary', 'active');
            b.classList.remove('btn-warning', 'btn-success', 'btn-danger');
            
            if (b.dataset.status === 'pending') {
              b.classList.add('btn-outline-warning');
            } else if (b.dataset.status === 'approved') {
              b.classList.add('btn-outline-success');
            } else if (b.dataset.status === 'rejected') {
              b.classList.add('btn-outline-danger');
            } else {
              b.classList.add('btn-outline-primary');
            }
          });
          
          // Add active class to clicked button
          btn.classList.remove('btn-outline-primary', 'btn-outline-warning', 'btn-outline-success', 'btn-outline-danger');
          
          if (btn.dataset.status === 'pending') {
            btn.classList.add('btn-warning', 'active');
          } else if (btn.dataset.status === 'approved') {
            btn.classList.add('btn-success', 'active');
          } else if (btn.dataset.status === 'rejected') {
            btn.classList.add('btn-danger', 'active');
          } else {
            btn.classList.add('btn-primary', 'active');
          }
          
          const filteredRequests = getRequestsByStatus(btn.dataset.status);
          populateRequestsTable(filteredRequests);
        });
      });
    }

    // Initialize the dashboard
    document.addEventListener('DOMContentLoaded', () => {
      updateStats();
      setupCharts();
      setupEventListeners();
      populateRequestsTable(mockRequests);
    });
  </script>

@endsection