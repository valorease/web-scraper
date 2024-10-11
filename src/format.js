import { JSDOM } from "jsdom";

/**
 * @param {string} content 
 * @return {Document}
 */
const html = (content) => (new JSDOM(content)).window.document;

/**
 * @param {string} target 
 * @param {string} body 
 */
export const format = (target, body) => {
  switch (target) {
    case "ML": {
      return formatML(body);
    }

    case "AZ": {
      return formatML(body);
    }

    default: {
      throw "";
    }
  }
}

/**
 * @param {string} price 
 * @return {string}
 */
const priceNumber = (price) => {
  return price.replace(/[^0-9]/g, "");
};

/**
 * @param {string} body 
 * @return {string[]}
 */
const formatML = (body) => {
  const document = html(body);

  const priceElements = document.querySelectorAll(
    `div.ui-search-item__group__element .ui-search-price .ui-search-price__second-line span[aria-roledescription="Preço"] span.andes-money-amount__fraction`
  );

  return Array.from(priceElements).map((element) => {
    const price = element.textContent ?? "";

    const cents =
      element.parentElement?.querySelector(".andes-money-amount__cents")
        ?.textContent ?? "00";

    return priceNumber(price + cents);
  });
}

/**
 * @param {string} body 
 */
const formatAZ = (body) => {

}