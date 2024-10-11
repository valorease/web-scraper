import { format } from "./format.js";

const HEADERS = {
  "User-Agent":
    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/109.0",
  "Accept-Language": "en-US,en;q=0.5",
  "Accept-Encoding": "gzip, deflate, br",
  Accept:
    "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8",
  Referer: "http://www.google.com/",
};


/**
 * @param {[string, string][]} urls
 */
const getByUrls = async (urls) => {
  const results = [];

  for (const [target, url] of urls) {
    const result = await fetch(url, {
      headers: HEADERS
    })

    const body = await result.text();

    results.push({
      url: url,
      target: target,
      prices: format(target, body)
    });
  }

  return results;
}

const getByTargets = async (slug, targets) => {
  const results = [];

  for (const [target, url] of urls) {
    const result = await fetch(url, {
      headers: HEADERS
    })

    const body = await result.text();

    results.push({
      url: url,
      target: target,
      prices: format(target, body)
    });
  }

  return results;
}

export const app = async () => {
  const result = await fetch("/api/product/search");

  /**
   * @typedef {{
   *    id: number,
   *    name: string,
   *    slug: string,
   *    urls: [string, string][]|null,
   *    targets: string[]|null
   * }} Result  
   */

  /** @type {Result} */
  const { id, name, slug, urls, targets } = await result.json();

  let results = [];

  if (urls) {
    results = [...results, ...await getByUrls(urls)];
  }

  if (targets) {
    results = [...results, ...await getByTargets(slug, targets)];
  }

  await fetch("/api/product/result", {
    method: "POST",
    body: JSON.stringify({
      id: id,
      results: results,
    })
  })
};


