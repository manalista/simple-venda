--
-- PostgreSQL database dump
--

-- Dumped from database version 14.4 (Ubuntu 14.4-0ubuntu0.22.04.1)
-- Dumped by pg_dump version 14.4 (Ubuntu 14.4-0ubuntu0.22.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: vendas; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA vendas;


ALTER SCHEMA vendas OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: itens_vendas; Type: TABLE; Schema: vendas; Owner: vendas
--

CREATE TABLE vendas.itens_vendas (
    id integer NOT NULL,
    venda_id integer NOT NULL,
    produto_id integer NOT NULL,
    quantidade numeric(10,2) NOT NULL,
    total_liquido_item numeric(10,2) NOT NULL,
    total_bruto_item numeric(10,2) NOT NULL,
    total_imposto_item numeric(10,2) NOT NULL
);


ALTER TABLE vendas.itens_vendas OWNER TO vendas;

--
-- Name: itens_vendas_id_seq; Type: SEQUENCE; Schema: vendas; Owner: vendas
--

CREATE SEQUENCE vendas.itens_vendas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendas.itens_vendas_id_seq OWNER TO vendas;

--
-- Name: itens_vendas_id_seq; Type: SEQUENCE OWNED BY; Schema: vendas; Owner: vendas
--

ALTER SEQUENCE vendas.itens_vendas_id_seq OWNED BY vendas.itens_vendas.id;


--
-- Name: tipos_produtos; Type: TABLE; Schema: vendas; Owner: vendas
--

CREATE TABLE vendas.tipos_produtos (
    id integer NOT NULL,
    descricao character varying(30) NOT NULL,
    valor_imposto numeric(10,2) NOT NULL
);


ALTER TABLE vendas.tipos_produtos OWNER TO vendas;

--
-- Name: newtable_id_seq; Type: SEQUENCE; Schema: vendas; Owner: vendas
--

CREATE SEQUENCE vendas.newtable_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendas.newtable_id_seq OWNER TO vendas;

--
-- Name: newtable_id_seq; Type: SEQUENCE OWNED BY; Schema: vendas; Owner: vendas
--

ALTER SEQUENCE vendas.newtable_id_seq OWNED BY vendas.tipos_produtos.id;


--
-- Name: produtos; Type: TABLE; Schema: vendas; Owner: vendas
--

CREATE TABLE vendas.produtos (
    id integer NOT NULL,
    nome character varying(20) NOT NULL,
    descricao character varying(100),
    valor numeric(10,2) NOT NULL,
    tipo_id integer NOT NULL
);


ALTER TABLE vendas.produtos OWNER TO vendas;

--
-- Name: produtos_id_seq; Type: SEQUENCE; Schema: vendas; Owner: vendas
--

CREATE SEQUENCE vendas.produtos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendas.produtos_id_seq OWNER TO vendas;

--
-- Name: produtos_id_seq; Type: SEQUENCE OWNED BY; Schema: vendas; Owner: vendas
--

ALTER SEQUENCE vendas.produtos_id_seq OWNED BY vendas.produtos.id;


--
-- Name: vendas; Type: TABLE; Schema: vendas; Owner: vendas
--

CREATE TABLE vendas.vendas (
    id integer NOT NULL,
    data_hora timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    valor_total_liquido numeric(10,2) NOT NULL,
    valor_total_impostos numeric(10,2) NOT NULL,
    valor_total_bruto numeric(10,2) NOT NULL
);


ALTER TABLE vendas.vendas OWNER TO vendas;

--
-- Name: vendas_id_seq; Type: SEQUENCE; Schema: vendas; Owner: vendas
--

CREATE SEQUENCE vendas.vendas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendas.vendas_id_seq OWNER TO vendas;

--
-- Name: vendas_id_seq; Type: SEQUENCE OWNED BY; Schema: vendas; Owner: vendas
--

ALTER SEQUENCE vendas.vendas_id_seq OWNED BY vendas.vendas.id;


--
-- Name: itens_vendas id; Type: DEFAULT; Schema: vendas; Owner: vendas
--

ALTER TABLE ONLY vendas.itens_vendas ALTER COLUMN id SET DEFAULT nextval('vendas.itens_vendas_id_seq'::regclass);


--
-- Name: produtos id; Type: DEFAULT; Schema: vendas; Owner: vendas
--

ALTER TABLE ONLY vendas.produtos ALTER COLUMN id SET DEFAULT nextval('vendas.produtos_id_seq'::regclass);


--
-- Name: tipos_produtos id; Type: DEFAULT; Schema: vendas; Owner: vendas
--

ALTER TABLE ONLY vendas.tipos_produtos ALTER COLUMN id SET DEFAULT nextval('vendas.newtable_id_seq'::regclass);


--
-- Name: vendas id; Type: DEFAULT; Schema: vendas; Owner: vendas
--

ALTER TABLE ONLY vendas.vendas ALTER COLUMN id SET DEFAULT nextval('vendas.vendas_id_seq'::regclass);


--
-- Data for Name: itens_vendas; Type: TABLE DATA; Schema: vendas; Owner: vendas
--

COPY vendas.itens_vendas (id, venda_id, produto_id, quantidade, total_liquido_item, total_bruto_item, total_imposto_item) FROM stdin;
\.


--
-- Data for Name: produtos; Type: TABLE DATA; Schema: vendas; Owner: vendas
--

COPY vendas.produtos (id, nome, descricao, valor, tipo_id) FROM stdin;
1	Feijão	Feijão carioca 1kg	9.85	1
2	Arroz 	Arroz branco 5kg	19.95	1
\.


--
-- Data for Name: tipos_produtos; Type: TABLE DATA; Schema: vendas; Owner: vendas
--

COPY vendas.tipos_produtos (id, descricao, valor_imposto) FROM stdin;
1	Grãos	0.10
2	Laticínios	0.15
3	Bebidas não alcoólicas	0.07
4	Bebidas alcoólicas	0.35
5	Padaria	0.15
6	Material de higiene	0.15
7	Materiais de limpeza	0.25
\.


--
-- Data for Name: vendas; Type: TABLE DATA; Schema: vendas; Owner: vendas
--

COPY vendas.vendas (id, data_hora, valor_total_liquido, valor_total_impostos, valor_total_bruto) FROM stdin;
\.


--
-- Name: itens_vendas_id_seq; Type: SEQUENCE SET; Schema: vendas; Owner: vendas
--

SELECT pg_catalog.setval('vendas.itens_vendas_id_seq', 1, false);


--
-- Name: newtable_id_seq; Type: SEQUENCE SET; Schema: vendas; Owner: vendas
--

SELECT pg_catalog.setval('vendas.newtable_id_seq', 7, true);


--
-- Name: produtos_id_seq; Type: SEQUENCE SET; Schema: vendas; Owner: vendas
--

SELECT pg_catalog.setval('vendas.produtos_id_seq', 2, true);


--
-- Name: vendas_id_seq; Type: SEQUENCE SET; Schema: vendas; Owner: vendas
--

SELECT pg_catalog.setval('vendas.vendas_id_seq', 1, false);


--
-- Name: itens_vendas itens_vendas_pk; Type: CONSTRAINT; Schema: vendas; Owner: vendas
--

ALTER TABLE ONLY vendas.itens_vendas
    ADD CONSTRAINT itens_vendas_pk PRIMARY KEY (id);


--
-- Name: tipos_produtos newtable_pk; Type: CONSTRAINT; Schema: vendas; Owner: vendas
--

ALTER TABLE ONLY vendas.tipos_produtos
    ADD CONSTRAINT newtable_pk PRIMARY KEY (id);


--
-- Name: produtos produtos_pk; Type: CONSTRAINT; Schema: vendas; Owner: vendas
--

ALTER TABLE ONLY vendas.produtos
    ADD CONSTRAINT produtos_pk PRIMARY KEY (id);


--
-- Name: vendas vendas_pk; Type: CONSTRAINT; Schema: vendas; Owner: vendas
--

ALTER TABLE ONLY vendas.vendas
    ADD CONSTRAINT vendas_pk PRIMARY KEY (id);


--
-- Name: produtos produtos_fk; Type: FK CONSTRAINT; Schema: vendas; Owner: vendas
--

ALTER TABLE ONLY vendas.produtos
    ADD CONSTRAINT produtos_fk FOREIGN KEY (tipo_id) REFERENCES vendas.tipos_produtos(id);


--
-- Name: itens_vendas protudo_venda_fk; Type: FK CONSTRAINT; Schema: vendas; Owner: vendas
--

ALTER TABLE ONLY vendas.itens_vendas
    ADD CONSTRAINT protudo_venda_fk FOREIGN KEY (produto_id) REFERENCES vendas.produtos(id);


--
-- Name: itens_vendas venda_fk; Type: FK CONSTRAINT; Schema: vendas; Owner: vendas
--

ALTER TABLE ONLY vendas.itens_vendas
    ADD CONSTRAINT venda_fk FOREIGN KEY (venda_id) REFERENCES vendas.vendas(id);


--
-- PostgreSQL database dump complete
--

