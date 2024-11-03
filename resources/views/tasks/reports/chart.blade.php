<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
</head>
<body>
    <div style="width: 600px; height: 400px;">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        var labels = @json($labels);
        var data = @json($data);

        var ctx = document.getElementById('myChart').getContext('2d');

        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                }]
            },
            options: {
                layout: {
                    padding: {
                        bottom: 25,
                        top: 25
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: "Task completion statistics",
                    }
                }
            },
        });
    </script>
</body>
</html>
