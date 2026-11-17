// Reads [{spec, input, onAxis?, params?}] from stdin, prints the formatted strings as JSON.
// Used by tests/Js/NumberFormatJsTest.php to run the browser formatter under Node.
import { numberFormatter } from '../../resources/js/number-format.js'

let raw = ''
for await (const chunk of process.stdin) {
    raw += chunk
}

const results = JSON.parse(raw).map(({ spec, input }) => {
    const format = numberFormatter(spec)
    return format(input)
})

console.log(JSON.stringify(results))
