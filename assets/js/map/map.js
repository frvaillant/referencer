import markerIconPng from "../../../node_modules/leaflet/dist/images/marker-icon.png"
import {Icon} from "leaflet";
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('map')) {
        const mymap = L.map('map', {
            scrollWheelZoom: false,
            preferCanvas: true,
            zoomControl: false,
        }).setView([47, 2], 6)
        const bg = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {}).addTo(mymap);
        const markers = {}

        fetch('/api/studies', {
            method: "GET"
        }).then(response => {
            if (200 === response.status) {
                return response.json()
            }
        }).then(studies => {
            studies = studies['hydra:member']

            for (let key in studies) {
                if(studies[key].city && studies[key].city.latitude && studies[key].city.longitude) {
                    const text = document.createElement('p')
                    text.innerText = studies[key].title
                    /*
                        const mark = L.marker([studies[key].city.latitude, studies[key].city.longitude], {
                            icon: new Icon({iconUrl: markerIconPng})
                        }).addTo(mymap).bindPopup(text)

-1.8 / 5.85
                     */
                    let lat = studies[key].city.latitude
                    let lng = studies[key].city.longitude
                    if (document.getElementById('map').classList.contains('map-example')) {
                        lat -= 1.8
                        lng += 6
                    }
                    const mark = L.circle([lat, lng], {
                        radius: 5000,
                        fillColor: '#37474f',
                        color: '#37474f',
                        fillOpacity:1,
                    }).addTo(mymap).bindPopup(text);

                }
            }
            window.status = 'MapLoaded'
        })
    }


})

