@extends('layouts.app')

@section('content')

<div class="container-fluid py-2 px-3 px-sm-4 px-lg-5" style="margin-top: 0;">
  <div class="col-12">
    <div class="card">
      <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <h5 class="card-title mb-0">Demandes Récentes</h5>
          <div class="d-flex flex-wrap mt-2 mt-sm-0">
            <div class="search-container me-2 mb-2 mb-sm-0">
              <i class="bi bi-search search-icon"></i>
              <input type="text" class="form-control" id="searchInput" placeholder="Rechercher...">
            </div>
            <div class="status-filter">
              <button class="btn btn-sm btn-primary active" data-status="all">Toutes</button>
              <button class="btn btn-sm btn-outline-warning" data-status="pending">En attente</button>
              <button class="btn btn-sm btn-outline-success" data-status="approved">Approuvées</button>
              <button class="btn btn-sm btn-outline-danger" data-status="rejected">Rejetées</button>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-requests mb-0">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Citoyen</th>
                <th scope="col">Document</th>
                <th scope="col">Date</th>
                <th scope="col">Statut</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody id="requestsTableBody">
              <!-- Table rows will be dynamically populated -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
      },
      {
        id: "REQ-2023-1253",
        citizen: {
          id: "CIT-1987",
          name: "Claire Martin",
          email: "claire.martin@example.com",
          phone: "06 98 76 54 32",
          address: "8 Avenue Victor Hugo, 75016 Paris",
          dateOfBirth: "1990-09-23"
        },
        documentType: "Acte de mariage",
        requestDate: "2023-05-14",
        status: "pending",
        urgent: false,
        notes: "",
        documents: [
          {
            name: "Pièce d'identité",
            type: "image/jpeg",
            size: "0.9 MB",
            uploadDate: "2023-05-14"
          },
          {
            name: "Livret de famille",
            type: "application/pdf",
            size: "1.5 MB",
            uploadDate: "2023-05-14"
          }
        ],
        history: [
          {
            date: "2023-05-14 16:12",
            action: "Soumission de la demande",
            agent: "-",
            comment: "Demande créée par le citoyen"
          }
        ]
      },
      {
        id: "REQ-2023-1252",
        citizen: {
          id: "CIT-3456",
          name: "Lucas Bernard",
          email: "lucas.bernard@example.com",
          phone: "07 23 45 67 89",
          address: "25 Rue du Commerce, 75015 Paris",
          dateOfBirth: "1978-11-30"
        },
        documentType: "Certificat de vie",
        requestDate: "2023-05-13",
        status: "approved",
        urgent: false,
        notes: "Document nécessaire pour la caisse de retraite étrangère.",
        documents: [
          {
            name: "Pièce d'identité",
            type: "image/jpeg",
            size: "1.1 MB",
            uploadDate: "2023-05-13"
          }
        ],
        history: [
          {
            date: "2023-05-13 09:34",
            action: "Soumission de la demande",
            agent: "-",
            comment: "Demande créée par le citoyen"
          },
          {
            date: "2023-05-14 11:20",
            action: "Documents vérifiés",
            agent: "Philippe Dupont",
            comment: "Documents conformes"
          },
          {
            date: "2023-05-15 14:15",
            action: "Demande approuvée",
            agent: "Sophie Martin",
            comment: "Certificat généré et envoyé par email"
          }
        ]
      },
      {
        id: "REQ-2023-1251",
        citizen: {
          id: "CIT-2789",
          name: "Emma Leroux",
          email: "emma.leroux@example.com",
          phone: "06 34 56 78 90",
          address: "42 Boulevard Haussmann, 75009 Paris",
          dateOfBirth: "1995-06-18"
        },
        documentType: "Certificat de non revenu",
        requestDate: "2023-05-12",
        status: "rejected",
        urgent: false,
        notes: "",
        documents: [
          {
            name: "Pièce d'identité",
            type: "image/jpeg",
            size: "0.8 MB",
            uploadDate: "2023-05-12"
          },
          {
            name: "Avis d'imposition",
            type: "application/pdf",
            size: "1.2 MB",
            uploadDate: "2023-05-12"
          }
        ],
        history: [
          {
            date: "2023-05-12 14:56",
            action: "Soumission de la demande",
            agent: "-",
            comment: "Demande créée par le citoyen"
          },
          {
            date: "2023-05-13 10:30",
            action: "Documents vérifiés",
            agent: "Marie Laurent",
            comment: "Document illisible"
          },
          {
            date: "2023-05-14 09:45",
            action: "Demande rejetée",
            agent: "Sophie Martin",
            comment: "L'avis d'imposition fourni n'est pas lisible. Veuillez soumettre une version plus claire."
          }
        ]
      },
      {
        id: "REQ-2023-1250",
        citizen: {
          id: "CIT-4567",
          name: "Hugo Petit",
          email: "hugo.petit@example.com",
          phone: "07 65 43 21 09",
          address: "3 Rue Mouffetard, 75005 Paris",
          dateOfBirth: "1982-02-25"
        },
        documentType: "Acte de décès",
        requestDate: "2023-05-11",
        status: "approved",
        urgent: true,
        notes: "Demande concernant le décès d'un parent.",
        documents: [
          {
            name: "Pièce d'identité",
            type: "image/jpeg",
            size: "1.0 MB",
            uploadDate: "2023-05-11"
          },
          {
            name: "Certificat de décès",
            type: "application/pdf",
            size: "0.7 MB",
            uploadDate: "2023-05-11"
          },
          {
            name: "Livret de famille",
            type: "application/pdf",
            size: "1.3 MB",
            uploadDate: "2023-05-11"
          }
        ],
        history: [
          {
            date: "2023-05-11 11:23",
            action: "Soumission de la demande",
            agent: "-",
            comment: "Demande créée par le citoyen"
          },
          {
            date: "2023-05-11 15:40",
            action: "Documents vérifiés",
            agent: "Philippe Dupont",
            comment: "Documents conformes, traitement prioritaire"
          },
          {
            date: "2023-05-12 09:15",
            action: "Demande approuvée",
            agent: "Sophie Martin",
            comment: "Acte généré et envoyé par email"
          }
        ]
      },
      {
        id: "REQ-2023-1249",
        citizen: {
          id: "CIT-5678",
          name: "Léa Moreau",
          email: "lea.moreau@example.com",
          phone: "06 54 32 10 98",
          address: "17 Rue de Rivoli, 75001 Paris",
          dateOfBirth: "1993-12-07"
        },
        documentType: "Certificat d'entretien",
        requestDate: "2023-05-10",
        status: "pending",
        urgent: false,
        notes: "",
        documents: [
          {
            name: "Pièce d'identité",
            type: "image/jpeg",
            size: "0.9 MB",
            uploadDate: "2023-05-10"
          },
          {
            name: "Justificatif de domicile",
            type: "application/pdf",
            size: "0.6 MB",
            uploadDate: "2023-05-10"
          }
        ],
        history: [
          {
            date: "2023-05-10 13:45",
            action: "Soumission de la demande",
            agent: "-",
            comment: "Demande créée par le citoyen"
          },
          {
            date: "2023-05-11 16:20",
            action: "Documents vérifiés",
            agent: "Marie Laurent",
            comment: "Documents conformes"
          }
        ]
      }
    ];

    // Statistics data
    const statsData = {
      totalRequests: 324,
      pendingRequests: 24,
      approvedRequests: 276,
      rejectedRequests: 24,
      monthlyGrowth: 8,
      weeklyChange: -3
    };

    // Document type distribution data for chart
    const documentTypeData = {
      labels: ['Extraits de naissance', 'Actes de mariage', 'Actes de décès', 'Certificats de vie', 'Certificats de non revenu', 'Certificats d\'entretien'],
      data: [125, 87, 42, 31, 18, 21]
    };

    // Monthly activity data for chart
    const monthlyActivityData = {
      labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
      datasets: [
        {
          label: 'Demandes',
          data: [28, 35, 40, 42, 50, 45, 55, 40, 48, 52, 53, 41],
          borderColor: '#0056b3',
          backgroundColor: 'rgba(0, 86, 179, 0.1)',
          tension: 0.4,
          fill: true
        },
        {
          label: 'Approuvées',
          data: [25, 30, 35, 38, 45, 40, 48, 35, 42, 48, 45, 38],
          borderColor: '#28a745',
          backgroundColor: 'rgba(40, 167, 69, 0.1)',
          tension: 0.4,
          fill: true
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
