
import Chart from "chart.js/auto";
import ChartDataLabels, { Context } from 'chartjs-plugin-datalabels';

type StatType = {
    data: []
    color: string
    type: 'statistic'
}

type DatasetsType = Array<{
    fill: string
    data: number[]
    backgroundColor: string
    borderColor: string
    borderWidth: number
    tension?: number
    pointBackgroundColor?: string
}>

type ChartType = {
    labels: Array<string>
    data: []
    color: string
    type: 'chart'
    datasets: DatasetsType
    dataLabel?: boolean
}

export default function initChart({
    color,
    ...config
}: StatType | ChartType) {
    return {
        init: function () {
            const colorMap = {
                primary: {
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: 'rgba(99, 102, 241,1)'
                },
                success: {
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgba(16, 185, 129,1)'
                },
                danger: {
                    backgroundColor: 'rgba(244, 63, 94, 0.2)',
                    borderColor: 'rgba(244, 63, 94,1)'
                },
                warning: {
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: 'rgba(245, 158, 11,1)'
                },
                info: {
                    backgroundColor: 'rgba(14, 165, 233, 0.2)',
                    borderColor: 'rgba(14, 165, 233, 1)'
                },

            }
            if (config.type == 'statistic') {
                new Chart(this.$refs.canvas, {
                    type: 'line',
                    options: {
                        animation: {
                            duration: 0,
                        },
                        elements: {
                            point: {
                                radius: 0,
                            },
                        },
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                display: false,
                            },
                            y: {
                                display: false,
                            },
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        },
                    },
                    data: {
                        labels: config.data,
                        datasets: [{
                            fill: 'start',
                            data: config.data,
                            backgroundColor: [
                                colorMap[color].backgroundColor,
                            ],
                            borderColor: [
                                colorMap[color].borderColor,
                            ],
                            borderWidth: 2,
                            tension: 0.5,
                        }],
                    },
                });
            } else if (config.type == 'chart') {
                const datasets = config.datasets.map(item => {
                    item.backgroundColor = item.backgroundColor ? (colorMap[item.backgroundColor].backgroundColor) : (colorMap[color].backgroundColor)
                    item.borderColor = item.borderColor ? (colorMap[item.borderColor].borderColor) : (colorMap[color].borderColor)
                    item.borderWidth = item.borderWidth ?? 2
                    item.tension = item.tension ?? 0.3
                    item.pointBackgroundColor = item.pointBackgroundColor ? (colorMap[item.pointBackgroundColor].borderColor) : (colorMap[color].borderColor)
                    return item
                })

                let plugins: Array<any> = []
                let pluginsConfig: any = {}

                if (config.dataLabel) {
                    plugins.push(ChartDataLabels)
                    pluginsConfig.datalabels = {
                        display: true,
                        align: 'end',
                        anchor: 'end',
                        color: function (ctx: any) {
                            return 'white'
                        },
                        borderColor: function (ctx: Context) {
                            return ctx.dataset.borderColor as string
                        },
                        borderWidth: 2,
                        borderRadius: (ctx: Context) => {
                            return ctx.active ? 5 : 20
                        },
                        padding: 3,
                        backgroundColor: (ctx: Context) => {
                            return ctx.dataset.borderColor as string
                        },
                        font: (ctx: Context) => {
                            return {
                                weight: ctx.active ? 'bold' : 'normal',
                                size: ctx.active ? 14 : 10,
                            }
                        },
                    }
                }

                new Chart(this.$refs.canvas, {
                    type: 'line',
                    plugins: plugins,
                    options: {
                        responsive: true,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: true, position: 'bottom' },
                            ...pluginsConfig
                        },
                    },
                    data: {
                        labels: config.labels,
                        datasets: datasets
                    },
                });
            }
        }
    }
}

window['initChart'] = initChart