var ctx2 = document.getElementById('myChart1').getContext('2d');
var chart2 = new Chart(ctx2, {
// The type of chart we want to create
type: 'pie',

// The data for our dataset
data: {
    labels: ["Двойки","Тройки", "Четверки", "Пятерки"],
    datasets: [{
        label: "Успеваемость",
        backgroundColor: ['red', 'yellow', 'lightgreen', 'green'],
        borderColor: 'rgb(255, 99, 132)',
        data: masNum,
    }]
},

// Configuration options go here
options: {}
});
