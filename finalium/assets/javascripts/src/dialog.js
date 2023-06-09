// Finalium dialog stuff duct-taped on top of bootstrap 5.3.0 js, yikes.

import BaseComponent from './base-component.js'
import SelectorEngine from "./dom/selector-engine";
import EventHandler from "./dom/event-handler";

const NAME = 'dialog'
const DATA_KEY = 'fl.dialog'
const EVENT_KEY = `.${DATA_KEY}`
const DATA_API_KEY = '.data-api'
const EVENT_CLICK_DATA_API = `click${EVENT_KEY}${DATA_API_KEY}`

const SELECTOR_DIALOG = '.dialog'
const SELECTOR_DATA_TOGGLE = '[data-fl-toggle="dialog"]'

const Default = {
}

const DefaultType = {
}

class Dialog extends BaseComponent {
    constructor(element, config) {
        super(element, config)

        this._dialog = SelectorEngine.findOne(SELECTOR_DIALOG, this._element)
    }

    // Getters
    static get Default() {
        return Default
    }

    static get DefaultType() {
        return DefaultType
    }

    static get NAME() {
        return NAME
    }
}

EventHandler.on(document, EVENT_CLICK_DATA_API, SELECTOR_DATA_TOGGLE, function (event) {
    const target = SelectorEngine.getElementFromSelector(this)

    console.log(target)

    target.showModal()
})

export default Dialog