// The world map as an ES module, so Filament serves it as an Alpine component
// asset that is requested only by a chart whose option uses the map (see
// `registerMaps` in index.js). Source and licence: resources/geo, NOTICE.
import world from '../../geo/world-countries.json'

export default world
