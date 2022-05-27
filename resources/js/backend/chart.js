import Chart from 'chart.js/auto';


const bar = (elementId, labels, percentage, colors, type, label) => {

const trybar = document.getElementById(elementId);
  const data = {
    labels:  [label],
    datasets: [
      {
        label: labels['pro'],
        data: percentage['pro'],
        backgroundColor: colors[0],
        borderColor: colors[0],
        borderWidth: 1
      },
      {
        label: labels['non_pro'],
        data: percentage['non_pro'],
        backgroundColor: colors[1],
        borderColor: colors[1],
        borderWidth: 1
      },
      {
        label: labels['undecided'],
        data: percentage['undecided'],
        backgroundColor: colors[2],
        borderColor: colors[2],
        borderWidth: 1
      },
      {
        label: labels['untagged'],
        data: percentage['untagged'],
        backgroundColor: colors[3],
        borderColor: colors[3],
        borderWidth: 1
      }

    ]
  };

  const config = {
    type: type,
    data: data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    },
  };
  return new Chart(trybar, config);
}

const pie = (elementId, labels, percentage, colors, type, label) => {
  const ctx = document.getElementById(elementId);

  const data = {
    labels: labels,
    datasets: [
      {
        label: label,
        data: percentage,
        backgroundColor: colors,
        hoverOffset: 2
      }
    ]
  };

  const config = {
    type: 'pie',
    data: data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    },
  };

  return new Chart(ctx, config);
}

document.addEventListener('DOMContentLoaded', () => {
  window.livewire.on('bar', data => {
    bar(data.elementId, data.labels, data.percentage, data.colors, data.type, data.label);
  });

  window.livewire.on('pie', data => {
    pie(data.elementId, data.labels, data.percentage, data.colors, data.type, data.label);
  });
});

