/*
*   Author: Wiebe Ranzijn
*   Description: Json Filter & Search, with Ajax
*/

// --------------------------------------------------------------------- Main variables

let search__input  = document.querySelector('.search__input')
let search__filter = document.querySelector('.search__filter')
let search__sort   = document.querySelector('.search__sort')
let list_container = document.querySelector('.list-container')
let url            = window.location

// --------------------------------------------------------------------- Get all search querys

function prepareSearchRequest () {
    ajax(
        url,
        {
            'search' : search__input.value,
            'filter' : search__filter.value,
            'sort'   : search__sort.value
        },
        'GET'
    )
}

// ---------------------------------------------------------------------

function prepareShoppingCart () {
    ajax(
        url,
        {
            'search' : "ShoppingCart",
            'filter' : "title",
            'sort'   : ""
        },
        'GET'
    )
}

// --------------------------------------------------------------------- Ajax request

const ajax = (url, parameters, method) => {
    if (method.toLowerCase() == 'get') {
        let string = ""
        for (let i in parameters) {
            let key = i
            let value = parameters[i];
            (key == 'sort') ? string += `${key}=${value}` : string += `${key}=${value}&`
        }
        parameters = string
    }

    new Promise ((resolve, reject) => {
        let xmlhttp = new XMLHttpRequest()
        xmlhttp.onreadystatechange = () => {
            if(this.readyState == 4 || this.status == 200) {
                let serverResponse = xmlhttp.responseText
                // console.log(xmlhttp.responseText)
                resolve(serverResponse)
            } else {
                reject(xmlhttp.status)
                // console.error(`status: ${xmlhttp.status}`)
                // console.error(xmlhttp.responseText)
                // console.error("xmlhttp failed")
            }

            list_container.innerHTML = xmlhttp.responseText;
        }
        console.log(`${url}?${parameters}`)
        xmlhttp.open(method, `${url}?${parameters}`, true)
        xmlhttp.send()
    })
}

// --------------------------------------------------------------------- Add item to cookies for webshop

let shopCounter = document.querySelector('.shop-counter__count')

function addCookie (value) {
    let expires = ""
    let date = new Date()
    date.setTime(date.getTime() + (12*24*60*60*1000)) // Keep the cookie for 12 days
    expires = "; expires=" + date.toUTCString()
    let cookieArray = getCookieValues(document.cookie)
    let e = false
    cookieArray.forEach((v) => {
         if (v[0] == value) {
             v[1]++
             e = true
         }
    })
    if (!e)
        cookieArray[cookieArray.length] = [value, 1];
    console.log(cookieArray)
    document.cookie = `items=` + JSON.stringify(cookieArray) + `${expires}; path=/`;
    console.log(document.cookie);
    let counter = 0;
    getCookieValues(document.cookie).forEach((c) => {
        counter += c[1];
    })
    shopCounter.innerHTML = counter;
}

// --------------------------------------------------------------------- Get cookie values

function getCookieValues (cookie) {
    if (!cookie) return [];
    let value = cookie.split("=")[1];
    return JSON.parse(value)
}

// --------------------------------------------------------------------- Event listeners

search__input.addEventListener('input', prepareSearchRequest)
search__filter.addEventListener('change', prepareSearchRequest)
search__sort.addEventListener('change', prepareSearchRequest)