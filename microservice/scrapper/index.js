const express = require('express');
const Scrapper = require('./src/Scrapper.js');
const app = express();
const PORT = 3000
const cors = require('cors');
require('dotenv').config();

app.use(cors());

app.get("/", async (req, res) => {

    let url = 'https://www.bauruempregos.com.br/home/vagas';
    let htmlClass = '.vaga';
    let scrapper = new Scrapper(url, htmlClass);
    let data = await scrapper.scrap();

    let dadosDetalhes = [];
    htmlClass = '.descricao-vaga';

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

    res.send(detalhes);

});

app.listen(process.env.PORT, () => console.log('listening on port ' + process.env.PORT))