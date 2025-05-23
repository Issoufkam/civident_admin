// Initialize and render dashboard charts

// Function to render the document type distribution chart
function renderDocumentTypeChart() {
  const ctx = document.getElementById('documentTypeChart');
  
  if (!ctx) return;
  
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: documentTypeData.labels,
      datasets: [{
        data: documentTypeData.data,
        backgroundColor: [
          '#0056b3',  // Primary
          '#2a9d8f',  // Secondary
          '#e9c46a',  // Accent
          '#f4a261',  // Orange
          '#e76f51',  // Red-Orange
          '#8338ec'   // Purple
        ],
        borderWidth: 2,
        borderColor: '#ffffff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 20,
            boxWidth: 12,
            font: {
              size: 11
            }
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.raw || 0;
              const total = context.dataset.data.reduce((acc, curr) => acc + curr, 0);
              const percentage = Math.round((value / total) * 100);
              return `${label}: ${value} (${percentage}%)`;
            }
          }
        }
      },
      cutout: '70%',
      animation: {
        animateScale: true,
        animateRotate: true
      }
    }
  });
}

// Function to render the monthly activity chart
function renderMonthlyActivityChart() {
  const ctx = document.getElementById('monthlyActivityChart');
  
  if (!ctx) return;
  
  new Chart(ctx, {
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
          align: 'end',
          labels: {
            boxWidth: 12,
            usePointStyle: true,
            font: {
              size: 11
            }
          }
        },
        tooltip: {
          mode: 'index',
          intersect: false
        }
      },
      scales: {
        x: {
          grid: {
            color: 'rgba(0, 0, 0, 0.05)'
          }
        },
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 10,
            precision: 0
          },
          grid: {
            color: 'rgba(0, 0, 0, 0.05)',
            drawBorder: false
          }
        }
      },
      elements: {
        point: {
          radius: 3,
          hoverRadius: 6
        },
        line: {
          tension: 0.4
        }
      },
      interaction: {
        mode: 'index',
        intersect: false
      }
    }
  });
}

// Initialize all charts
function initCharts() {
  renderDocumentTypeChart();
  renderMonthlyActivityChart();
}