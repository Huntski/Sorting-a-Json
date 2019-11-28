/*
*   Author: Wiebe Ranzijn
*   Description: Json Filter & Search, with Ajax
*/

if (!document.cookie) updateCookie([]);

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
                // reject(xmlhttp.status)
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
    let cookieArray = getCookieValues()
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
    getCookieValues().forEach((c) => {
        counter += c[1];
    })
    shopCounter.innerHTML = counter
}

// --------------------------------------------------------------------- Get cookie values

function getCookieValues () {
    if (!document.cookie) return []
    let value = document.cookie.split("items=").pop()
    value = value.split(";").shift()
    return JSON.parse(value)
}

// --------------------------------------------------------------------- Remove cookie based on title

function removeItem (title, id = null) {
    let shopCounter = document.querySelector('.shop-counter__count')
    let a = getCookieValues(),
        amount,
        n = []
        del = false
    a.forEach((v, k) => {
        if (v[0] == title) {
            v[1] -= 1
            if (!v[1]) {
                del = true
                delete a[k]
                delete document.querySelector(`${id}`)
                prepareShoppingCart()
            }
            amount = v[1]
            updateShoppingcart()
        }
    })
    a.forEach((x) => {
        if (x !== null) n.push(x)
    })
    updateCookie(n)
    if (del === false) document.querySelector(`.${id} > .cart-item__amount`).innerHTML = amount
    console.log(`.${id} > .cart-item__amount`)
    updateShoppingcart()
}

// --------------------------------------------------------------------- Updates items cookie based on array

function updateCookie (array) {
    cookieValues = getCookieValues();
    cookieValues.forEach((v, k) => {
        if (typeof v[1] === 'undefined') {
            document.cookie = `items=` + JSON.stringify([]) + getExpiredate() + `; path=/`;
            return;
        }
    });
    document.cookie = `items=` + JSON.stringify(array) + getExpiredate() + `; path=/`;
}

// --------------------------------------------------------------------- Event listeners

search__input.addEventListener('input', prepareSearchRequest)
search__filter.addEventListener('change', prepareSearchRequest)
search__sort.addEventListener('change', prepareSearchRequest)