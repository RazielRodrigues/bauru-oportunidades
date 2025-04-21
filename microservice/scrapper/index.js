const express = require('express');
const Scrapper = require('./src/Scrapper.js');
const app = express();
const PORT = 3000
const cors = require('cors');
require('dotenv').config();

app.use(cors());

app.get("/", async (req, res) => {

    let url = 'https://vagasbauru.com.br/vagas/';
    let htmlClass = '.status-publish';
    let scrapper = new Scrapper(url, htmlClass);
    let data = await scrapper.scrap();
    res.send(data);
/* 
    let dadosDetalhes = [];
    htmlClass = '.elementor-widget-container';

    let ids = [];
    data.map(element => ids.push(element.replace(/([^\d])+/gim, '')));

    ids.forEach(id => {
        url = `https://www.bauruempregos.com.br/home/detalhes/${id}`;
        scrapper = new Scrapper(url, htmlClass);
        dadosDetalhes.push(scrapper.scrapDetalhes());
    });

    let detalhes = [];
    await Promise.all(dadosDetalhes).then((values) => {
        detalhes.push(values);
     });

    res.send(detalhes); */

});

app.listen(process.env.PORT, () => console.log('listening on port ' + process.env.PORT))