<script>
    $.plot('#flotChart1', [{
        data: dashData2,
        color: '#00cccc'
    }], {
        series: {
            shadowSize: 0,
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.2
                    }, {
                        opacity: 0.2
                    }]
                }
            }
        },
        grid: {
            borderWidth: 0,
            labelMargin: 0
        },
        yaxis: {
            show: false,
            min: 0,
            max: 35
        },
        xaxis: {
            show: false,
            max: 50
        }
    });

    $.plot('#flotChart2', [{
        data: dashData2,
        color: '#00cccc'
    }], {
        series: {
            shadowSize: 0,
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.2
                    }, {
                        opacity: 0.2
                    }]
                }
            }
        },
        grid: {
            borderWidth: 0,
            labelMargin: 0
        },
        yaxis: {
            show: false,
            min: 0,
            max: 35
        },
        xaxis: {
            show: false,
            max: 50
        }
    });

    $.plot('#flotChart3', [{
        data: dashData2,
        color: '#00cccc'
    }], {
        series: {
            shadowSize: 0,
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.2
                    }, {
                        opacity: 0.2
                    }]
                }
            }
        },
        grid: {
            borderWidth: 0,
            labelMargin: 0
        },
        yaxis: {
            show: false,
            min: 0,
            max: 35
        },
        xaxis: {
            show: false,
            max: 50
        }
    });

    var datapie = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{
            data: [20, 20, 30, 5, 25],
            backgroundColor: ['#560bd0', '#007bff', '#00cccc', '#cbe0e3', '#74de00']
        }]
    };

    orderDashboard();

    function orderDashboard() {
        $.ajax({
            url: '{{ url('dashboard/order-statistic') }}',
            type: 'GET',
            cache: false,
            success: function(data) {

                var ctxBar = document.getElementById('chartBar');
                var myBarChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: data.bar.days,
                        datasets: [{
                            data: data.bar.total_order,
                            backgroundColor: '#560bd0',
                            label: 'Total Tambah Order'
                        }, {
                            data: data.bar.total_substraction_order,
                            backgroundColor: '#007bff',
                            label: 'Total Penukaran Point'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            filler: {
                                propagate: false
                            }
                        },
                        legend: {
                            display: true,
                            position: "bottom",
                            align: "center"
                        },
                        tooltips: {
                            enabled: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                ticks: {
                                    display: true,
                                    padding: 10,
                                    fontColor: "#6C7383"
                                },
                                gridLines: {
                                    display: false,
                                    drawBorder: false,
                                    color: 'transparent',
                                    zeroLineColor: '#eeeeee'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                ticks: {
                                    display: true,
                                    autoSkip: false,
                                    maxRotation: 0,
                                    max: data.total_sales_order,
                                    padding: 18,
                                    fontColor: "#6C7383"
                                },
                                gridLines: {
                                    display: true,
                                    color: "#f2f2f2",
                                    drawBorder: false
                                }
                            }]
                        },
                        elements: {
                            line: {
                                tension: .35
                            },
                            point: {
                                radius: 0
                            }
                        }
                    },
                });

                $('#total_order_span').html(data.donuts.total_order + ' ');
                $('#percentage_total_order_span').html('(' + data.donuts.total_order_percentage + '%)');
                $('#total_substraction_order_span').html(data.donuts.total_substraction_order + ' ');
                $('#percentage_total_substraction_order_span').html('(' + data.donuts
                    .total_substraction_order_percentage + '%)');

                var optionpie = {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: false,
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                };
                var datapie = {
                    labels: ['Tambah Order', 'Penukaran Point'],
                    datasets: [{
                        data: [data.donuts.total_order_percentage, data.donuts
                            .total_substraction_order_percentage
                        ],
                        backgroundColor: ['#560bd0', '#007bff']
                    }]
                };
                var ctxDonut = document.getElementById('chartDonut');
                var myPieChart = new Chart(ctxDonut, {
                    type: 'doughnut',
                    data: datapie,
                    options: optionpie
                });
            },
            error: function(xhr, error, code) {
                failed = true;
            }
        });
    }
</script>
