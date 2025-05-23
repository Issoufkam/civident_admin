// Request details functionality

// Function to show request details
function showRequestDetails(requestId) {
  const mainContent = document.getElementById('main-content');
  const requestDetailsTemplate = document.getElementById('request-details-template');
  
  if (!mainContent || !requestDetailsTemplate) return;
  
  // Get request data by ID
  const request = getRequestById(requestId);
  
  if (!request) {
    console.error('Request not found: ' + requestId);
    return;
  }
  
  // Clone the request details template content
  const detailsContent = requestDetailsTemplate.content.cloneNode(true);
  
  // Clear and append the new content
  mainContent.innerHTML = '';
  mainContent.appendChild(detailsContent);
  
  // Set active navigation
  setActiveNav('');  // No navigation item is active when viewing details
  
  // Add back button event listener
  document.getElementById('back-to-dashboard').addEventListener('click', function(e) {
    e.preventDefault();
    loadDashboardView();
  });
  
  // Update status badge
  const statusBadge = document.querySelector('.status-badge');
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
  
  statusBadge.classList.add(statusClass);
  statusBadge.textContent = statusLabel;
  
  // Populate request info
  const requestInfoContainer = document.querySelector('.request-info');
  
  // Format date
  const requestDate = new Date(request.requestDate).toLocaleDateString('fr-FR');
  const birthDate = new Date(request.citizen.dateOfBirth).toLocaleDateString('fr-FR');
  
  requestInfoContainer.innerHTML = `
    <div class="col-md-6 mb-3">
      <p class="info-label">ID de la demande</p>
      <p class="info-value">${request.id}</p>
    </div>
    <div class="col-md-6 mb-3">
      <p class="info-label">Type de document</p>
      <p class="info-value">${request.documentType}</p>
    </div>
    <div class="col-md-6 mb-3">
      <p class="info-label">Date de la demande</p>
      <p class="info-value">${requestDate}</p>
    </div>
    <div class="col-md-6 mb-3">
      <p class="info-label">Traitement prioritaire</p>
      <p class="info-value">${request.urgent ? '<span class="badge bg-danger">Oui</span>' : '<span class="badge bg-secondary">Non</span>'}</p>
    </div>
    <div class="col-12">
      <hr>
      <h6 class="mb-3">Informations du demandeur</h6>
    </div>
    <div class="col-md-6 mb-3">
      <p class="info-label">Nom complet</p>
      <p class="info-value">${request.citizen.name}</p>
    </div>
    <div class="col-md-6 mb-3">
      <p class="info-label">Date de naissance</p>
      <p class="info-value">${birthDate}</p>
    </div>
    <div class="col-md-6 mb-3">
      <p class="info-label">Email</p>
      <p class="info-value">${request.citizen.email}</p>
    </div>
    <div class="col-md-6 mb-3">
      <p class="info-label">Téléphone</p>
      <p class="info-value">${request.citizen.phone}</p>
    </div>
    <div class="col-12 mb-3">
      <p class="info-label">Adresse</p>
      <p class="info-value">${request.citizen.address}</p>
    </div>
  `;
  
  // Populate documents list
  const documentsListContainer = document.querySelector('.documents-list');
  
  if (request.documents && request.documents.length > 0) {
    request.documents.forEach(doc => {
      const documentElement = document.createElement('div');
      documentElement.className = 'col-md-6 mb-3';
      
      // Determine icon based on document type
      let iconClass = 'bi-file-earmark';
      if (doc.type.includes('image')) {
        iconClass = 'bi-file-earmark-image';
      } else if (doc.type.includes('pdf')) {
        iconClass = 'bi-file-earmark-pdf';
      }
      
      documentElement.innerHTML = `
        <div class="document-card d-flex align-items-center">
          <div class="document-icon">
            <i class="bi ${iconClass}"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="document-name">${doc.name}</h6>
            <p class="document-meta mb-0">
              ${doc.type} · ${doc.size} · Ajouté le ${doc.uploadDate}
            </p>
          </div>
          <div class="document-actions">
            <a href="#" title="Visualiser" data-bs-toggle="tooltip">
              <i class="bi bi-eye"></i>
            </a>
            <a href="#" title="Télécharger" data-bs-toggle="tooltip">
              <i class="bi bi-download"></i>
            </a>
          </div>
        </div>
      `;
      
      documentsListContainer.appendChild(documentElement);
    });
  } else {
    documentsListContainer.innerHTML = '<div class="col-12"><p class="text-muted">Aucun document fourni</p></div>';
  }
  
  // Populate request history
  const historyTableBody = document.querySelector('.request-history');
  
  if (request.history && request.history.length > 0) {
    request.history.forEach(entry => {
      const historyRow = document.createElement('tr');
      
      historyRow.innerHTML = `
        <td>${entry.date}</td>
        <td>${entry.action}</td>
        <td>${entry.agent}</td>
        <td>${entry.comment}</td>
      `;
      
      historyTableBody.appendChild(historyRow);
    });
  } else {
    historyTableBody.innerHTML = '<tr><td colspan="4" class="text-center">Aucun historique disponible</td></tr>';
  }
  
  // Set request notes if available
  const notesTextarea = document.getElementById('requestNotes');
  if (notesTextarea && request.notes) {
    notesTextarea.value = request.notes;
  }
  
  // Disable buttons based on current status
  if (request.status !== 'pending') {
    const approveBtn = document.querySelector('.btn-approve');
    const rejectBtn = document.querySelector('.btn-reject');
    
    if (approveBtn) approveBtn.disabled = true;
    if (rejectBtn) rejectBtn.disabled = true;
  }
  
  // Handle approve button
  const approveBtn = document.querySelector('.btn-confirm-approve');
  if (approveBtn) {
    approveBtn.addEventListener('click', function() {
      const comment = document.getElementById('approveComment').value;
      const notify = document.getElementById('sendNotificationApprove').checked;
      
      // Update request status
      const success = updateRequestStatus(requestId, 'approved', comment, 'Sophie Martin');
      
      if (success) {
        // Show success message
        showNotification('Demande approuvée avec succès');
        
        // Reload request details
        showRequestDetails(requestId);
      }
    });
  }
  
  // Handle reject button
  const rejectBtn = document.querySelector('.btn-confirm-reject');
  if (rejectBtn) {
    rejectBtn.addEventListener('click', function() {
      const reason = document.getElementById('rejectReason').value;
      const comment = document.getElementById('rejectComment').value;
      const notify = document.getElementById('sendNotificationReject').checked;
      
      if (!reason || !comment) {
        // Show error message
        showNotification('Veuillez remplir tous les champs obligatoires', 'danger');
        return;
      }
      
      // Update request status
      const success = updateRequestStatus(requestId, 'rejected', comment, 'Sophie Martin');
      
      if (success) {
        // Show success message
        showNotification('Demande rejetée avec succès');
        
        // Reload request details
        showRequestDetails(requestId);
      }
    });
  }
  
  // Handle save notes button
  const saveNotesBtn = document.querySelector('.btn-save-notes');
  if (saveNotesBtn) {
    saveNotesBtn.addEventListener('click', function() {
      const notes = document.getElementById('requestNotes').value;
      
      // Save notes
      const success = addNoteToRequest(requestId, notes, 'Sophie Martin');
      
      if (success) {
        // Show success message
        showNotification('Notes enregistrées avec succès');
      }
    });
  }
  
  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
}

// Function to display notification
function showNotification(message, type = 'success') {
  // Create notification element
  const notification = document.createElement('div');
  notification.className = `alert alert-${type} alert-dismissible fade show notification-toast`;
  notification.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;
  
  // Apply styles
  Object.assign(notification.style, {
    position: 'fixed',
    top: '20px',
    right: '20px',
    zIndex: '9999',
    minWidth: '300px',
    boxShadow: '0 0.5rem 1rem rgba(0, 0, 0, 0.15)',
    animation: 'fadeIn 0.3s ease-in-out'
  });
  
  // Add to document
  document.body.appendChild(notification);
  
  // Remove after 5 seconds
  setTimeout(() => {
    notification.classList.remove('show');
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, 5000);
}