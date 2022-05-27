import Chart from 'chart.js/auto';
class charts
{
    pie()
    {
        const ctx = document.getElementById('pie');
        const data = {
            labels: [
            'BBM',
            'Others',
            ],
            datasets: [{
            label: 'My First Dataset',
            data: [80, 20],
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
            ],
            hoverOffset: 2
            }]
        };

        const config = {
            type: 'pie',
            data: data,
        };

        const pie = new Chart(ctx, config);
    }

    bar()
    {

    }
}

export default charts
