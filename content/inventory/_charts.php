<script>

    // charts

    $(document).ready(function () {

        var salesDateSelect = $('#salesDateSelect');

        salesDateSelect.on('change', function () {
            salesChart(salesDateSelect.val());
        });

        salesChart(30);
    });

    function salesChart(dateSelected){
        {
            var myData = {
                selected: dateSelected,
            };

            $.post("chartSalesData.php", myData,
            function (data){

                var salesDate = [];
                var marks = [];

                for (var i in data) {
                    salesDate.push(data[i].sales_date);
                    marks.push(data[i].sales);
                }

                var chartdata = {
                    labels: salesDate,
                    datasets: [
                        {
                            label: dateSelected + '-Day Sales',
                            backgroundColor: 'rgba(0, 231, 89, 0.5)',
                            borderColor: 'rgba(0, 231, 89, 1)',
                            hoverBackgroundColor: '#CCCCCC',
                            hoverBorderColor: '#666666',
                            data: marks
                        }
                    ]
                };

                var graphTarget = $("#30DaySales");

                var lineGraph = new Chart(graphTarget, {
                    type: 'line',
                    data: chartdata
                });
            });
        }
    }

</script>