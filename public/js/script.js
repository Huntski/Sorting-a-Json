/*
*   Author: Wiebe Ranzijn
*   Description: Json Filter & Search, with Ajax
*/

// --------------------------------------------------------------------- Main variables

const search__input  = document.querySelector('.search__input')
const search__filter = document.querySelector('.search__filter')
const search__sort   = document.querySelector('.search__sort')
const list_container = document.querySelector('.list-container')
const url            = window.location
const baseurl        = url.protocol + "//" + url.host + '/school/Sorting-a-Json/';

// --------------------------------------------------------------------- Get all search querys

const prepareSearchRequest = function  () {
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

const prepareShoppingCart = function () {
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

const getExpiredate = function () {
    let expires = ""
    let date = new Date()
    date.setTime(date.getTime() + (12*24*60*60*1000)) // Keep the cookie for 12 days
    expires = "; expires=" + date.toUTCString()

    return expires
}

// --------------------------------------------------------------------- Add item to cookies for webshop

const addCookie = function (value) {
    let cookieArray = getCookieValues()
    let e = false
    cookieArray.forEach((v) => {
         if (v[0] == value) {
             v[1]++
             e = true
         }
    })
    if (!e) cookieArray[cookieArray.length] = [value, 1];
    document.cookie = `items=${JSON.stringify(cookieArray)}${getExpiredate()}; path=/`;
    updateShoppingcart()
}

// --------------------------------------------------------------------- Update shopping cart amount

let shopCounter = document.querySelector('.shop-counter__count')

const updateShoppingcart = function () {
    let counter = 0;
    getCookieValues().forEach((c) => {
        counter += c[1];
    })
    shopCounter.innerHTML = counter
}

// --------------------------------------------------------------------- Get cookie values

const getCookieValues = function () {
    if (document.cookie.search('items')) return []
    let value = document.cookie.split("items=").pop()
    value = value.split(";").shift()
    return JSON.parse(value)
}

// --------------------------------------------------------------------- Remove cookie based on title

const removeItem = function (title, id = null) {
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

const updateCookie = function (array) {
    cookieValues = getCookieValues();
    cookieValues.forEach((v, k) => {
        if (typeof v[1] === 'undefined') { // Resets all when the length of items does not exist
            document.cookie = `items=${JSON.stringify([])}${getExpiredate()}; path=/`;
            return;
        }
    });
    document.cookie = `items=` + JSON.stringify(array) + getExpiredate() + `; path=/`;
}

// --------------------------------------------------------------------- Event listeners

search__input.addEventListener('input', prepareSearchRequest)
search__filter.addEventListener('change', prepareSearchRequest)
search__sort.addEventListener('change', prepareSearchRequest)

// --------------------------------------------------------------------- Checks if items cookie exists

if (document.cookie.search('items') === -1) updateCookie([]);