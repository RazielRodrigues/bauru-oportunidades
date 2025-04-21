const axios = require('axios');
const cheerio = require('cheerio');

class Scrapper {

    constructor(url, htmlClass) {
        this.url = url;
        this.htmlClass = htmlClass;
    }

    async scrap() {
        try {

            const response = await axios(this.url);
            const html = response.data;
            const $ = cheerio.load(html);
            const articles = [];

            $(`${this.htmlClass}`, html).each(function () {
                // const title = $(this).text();
                console.log(html)

                const url = $(this).find('a').attr('href');
                console.log(url)

                articles.push(url);

            });

            return articles;
        } catch (error) {
            console.error(error);
        }
    }

    async scrapDetalhes() {
        try {

            const response = await axios(this.url);
            const html = response.data;
            const $ = cheerio.load(html);
            const articles = [];

            $(`${this.htmlClass}`, html).each(function () {

                const paragraphs = $(this).find('p').map(function (i, el) { return $(this).text(); }).toArray();

                const title = $(this).find('h1 > b').text();
                const description = $(this).find('pre').text();
                const type = 1;
                const city = 'Bauru'; //paragraphs[2];

                articles.push({
                    title,
                    description,
                    type,
                    city
                });

            });

            return articles;
        } catch (error) {
            console.error(error);
        }
    }

}

module.exports = Scrapper;