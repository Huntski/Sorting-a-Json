/*
*   Author: Wiebe Ranzijn
*   Description: Json Filter & Search, with Ajax
*/

if (!document.cookie) document.cookie = `items=` + JSON.stringify([]) + getExpiredate() +`; path=/`;

// --------------------------------------------------------------------- Main variables

let search__input  = document.querySelector('.search__input')
let search__filter = document.querySelector('.search__filter')
let search__sort   = document.querySelector('.search__sort')
let list_container = document.querySelector('.list-container')
let url            = window.location
// const baseurl = url.protocol + "//" + url.host + '/Sorting-a-Json/'; // localhost
const baseurl = url.protocol + "//" + url.host + '/Sorting-a-Json/'; // hosted

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

    window.history.pushState("String", "Title", `${baseurl}`)
}

// ---------------------------------------------------------------------

function prepareShoppingCart () {
    ajax(
        url,
        {
            'search' : "ShoppingCart",
            'filter' : "titel",
            'sort'   : ""
        },
        'GET'
    )

    window.history.pushState("String", "Title", `${baseurl}shoppingcart`)
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
            console.log(parameters)
        }
        console.log(`${url}?${parameters}`)
        xmlhttp.open(method, `${url}?${parameters}`, true)
        xmlhttp.send()
    })
}

// --------------------------------------------------------------------- Get experation date

function getExpiredate () {
    let expires = ""
    let date = new Date()
    date.setTime(date.getTime() + (12*24*60*60*1000)) // Keep the cookie for 12 days
    expires = "; expires=" + date.toUTCString()

    return expires
}

// --------------------------------------------------------------------- Add item to cookies for webshop

function addCookie (value) {
    let expires = getExpiredate();
    let cookieArray = getCookieValues(document.cookie)
    let e = false
    cookieArray.forEach((v) => {
         if (v[0] == value) {
             v[1]++
             e = true
         }
    })
    if (!e) cookieArray[cookieArray.length] = [value, 1];
    document.cookie = `items=` + JSON.stringify(cookieArray) + `${expires}; path=/`;
    updateShoppingcart()
}

// --------------------------------------------------------------------- Update shopping cart amount

let shopCounter = document.querySelector('.shop-counter__count')

function updateShoppingcart () {
    let counter = 0;
    getCookieValues(document.cookie).forEach((c) => {
        counter += c[1];
    })
    shopCounter.innerHTML = counter
}

// --------------------------------------------------------------------- Get cookie values

function getCookieValues (cookie) {
    if (!cookie) return []
    let value = cookie.split("=")[1]
    return JSON.parse(value)
}

// --------------------------------------------------------------------- Remove cookie based on title

function removeItem (title, id) {
    let expires = getExpiredate();
    let shopCounter = document.querySelector('.shop-counter__count')
    let a = getCookieValues(document.cookie),
        amount
    a.forEach((v, k) => {
        if (v[0] == title) {
            v[1]--
            if (!v[1]) {
                delete a[k]
                let n = []
                a.forEach((x) => {
                    if (x != null) n.push(x)
                })
                delete document.querySelector(`${id}`)
                document.cookie = `items=` + JSON.stringify(n) + `${expires}; path=/`;
                prepareShoppingCart()
                return
            }
            amount = v[1]
        }
    })
    document.querySelector(`.${id} > .cart-item__amount`).innerHTML = amount
    updateShoppingcart()
}

// --------------------------------------------------------------------- Event listeners

search__input.addEventListener('input', prepareSearchRequest)
search__filter.addEventListener('change', prepareSearchRequest)
search__sort.addEventListener('change', prepareSearchRequest)