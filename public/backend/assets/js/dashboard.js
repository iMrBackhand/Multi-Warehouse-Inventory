document.addEventListener("DOMContentLoaded", function () {
    let chart;

    function loadSalesChart(year) {
        fetch("/admin/dashboard/sales-chart/" + year)
            .then((response) => response.json())
            .then((data) => {
                if (chart) {
                    chart.destroy();
                }

                let options = {
                    series: [
                        {
                            name: "Total Sales",
                            data: data,
                        },
                    ],
                    chart: {
                        height: 350,
                        type: "bar",
                        toolbar: { show: false },
                    },
                    colors: ["#6f42c1"],
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            columnWidth: "40%",
                        },
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            shade: "light",
                            type: "vertical",
                            gradientToColors: ["#8b5fd9"],
                            opacityFrom: 1,
                            opacityTo: 0.85,
                        },
                    },
                    dataLabels: { enabled: false },
                    xaxis: {
                        categories: [
                            "Jan",
                            "Feb",
                            "Mar",
                            "Apr",
                            "May",
                            "Jun",
                            "Jul",
                            "Aug",
                            "Sep",
                            "Oct",
                            "Nov",
                            "Dec",
                        ],
                    },
                    yaxis: {
                        min: 0,
                        forceNiceScale: true,
                        labels: {
                            formatter: function (value) {
                                return "₱" + value.toLocaleString();
                            },
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function (value) {
                                return "₱" + value.toLocaleString();
                            },
                        },
                    },
                    grid: { borderColor: "#f1f1f1" },
                };

                chart = new ApexCharts(
                    document.querySelector("#sales-chart"),
                    options,
                );
                chart.render();

                fetch("/admin/dashboard/sales-summary/" + year)
                    .then((response) => response.json())
                    .then((data) => {
                        document.getElementById("totalSalesCard").innerHTML =
                            "₱" + data.totalSales;
                    });
            });
    }

    // Mag-fetch ng purchase summary base sa year, i-update yung Total Purchase card
    function loadPurchaseSummary(year) {
        fetch("/admin/dashboard/purchase-summary/" + year)
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("totalPurchaseCard").innerHTML =
                    "₱" + data.totalPurchase;
            });
    }

    function loadYearData(year) {
        loadSalesChart(year);
        loadPurchaseSummary(year);
    }

    loadYearData(document.querySelector("#yearFilter").value);

    document
        .querySelector("#yearFilter")
        .addEventListener("change", function () {
            loadYearData(this.value);
        });
});
