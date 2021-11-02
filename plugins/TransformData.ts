const Pivot = require('json-to-pivot-json')
const colors = require('nice-color-palettes');
const chartColors = [...colors[0], ...colors[1], ...colors[2], ...colors[3], ...colors[4]]


export function JsonToChartSeries(recordset, rowField, columnField, valueField, labelRender = null) {

    const data = Pivot(recordset, {
        row: rowField,
        column: columnField,
        value: valueField
    })

    const datasets = []
    const seriesName: any[] = [... new Set(recordset.map(rec => rec[columnField]))] 
    const labels = data.map(d => labelRender ? labelRender(d[rowField]) : d[rowField].toISOString().substring(0, 10)).sort()
    
    const series = {}
    seriesName.forEach(region => {
        series[region] = []
    })

    data.forEach(d => {
        seriesName.forEach(region => {
            series[region].push(d[region])
        })
    })

    seriesName.forEach((serieName, index) => {
        datasets.push({
            label: serieName,
            data: series[serieName],
            borderColor: chartColors[index],
            backgroundColor:chartColors[index],
            fill: false
        })
    })

    return ({
        datasets, series, labels
    })

}