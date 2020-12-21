class SpeedChart {
    chart;

    dataConfig;

    constructor(bindTo, dataUrl) {
        this.dataConfig = {
            x: 'x',
            xFormat: '%Y-%m-%d %H:%M:%S',
            url: dataUrl,
            mimeType: 'json',
            axes: {
               ping: 'y2'
            }
        };

        this.chart = c3.generate({
            bindto: bindTo,
            data: this.dataConfig,
            axis: {
                x: {
                    type: 'timeseries',
                    tick: {
                        format: '%Y-%m-%d %H:%M:%S'
                    },
                    extent: 100
                },
                y2: {
                    show: true
                }
            },
            subchart: {
                show: true
            },
        });
    }

    update() {
        this.chart.load(this.dataConfig);
    }
}
