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
    documentType: "Extrait de naissance",
    requestDate: "2023-05-15",
    status: "pending",
    urgent: true,
    notes: "Le demandeur a besoin du document rapidement pour une démarche administrative.",
    documents: [
      {
        name: "Pièce d'identité recto",
        type: "image/jpeg",
        size: "1.2 MB",
        uploadDate: "2023-05-15"
      },
      {
        name: "Pièce d'identité verso",
        type: "application/pdf",
        size: "0.8 MB",
        uploadDate: "2023-05-15"
      },
      {
        name: "Photo d'extrait de naissance lisible",
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

// Function to get a request by ID
function getRequestById(id) {
  return mockRequests.find(request => request.id === id);
}

// Function to get urgent requests
function getUrgentRequests() {
  return mockRequests.filter(request => request.urgent && request.status === 'pending');
}

// Function to get requests by status
function getRequestsByStatus(status) {
  if (status === 'all') {
    return mockRequests;
  }
  return mockRequests.filter(request => request.status === status);
}

// Function to update request status
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

// Function to add a note to a request
function addNoteToRequest(id, note, agent) {
  const request = getRequestById(id);
  if (request) {
    request.notes = note;
    return true;
  }
  return false;
}