--
-- PostgreSQL database dump
--

-- Dumped from database version 13.6
-- Dumped by pg_dump version 13.6

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: alunos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.alunos (
    id integer NOT NULL,
    nome character varying(255) NOT NULL,
    data_nascimento date,
    telefone character varying(20),
    sexo character varying(10),
    email character varying(255) NOT NULL
);


ALTER TABLE public.alunos OWNER TO postgres;

--
-- Name: alunos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.alunos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.alunos_id_seq OWNER TO postgres;

--
-- Name: alunos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.alunos_id_seq OWNED BY public.alunos.id;


--
-- Name: anamnese; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.anamnese (
    id integer NOT NULL,
    aluno_id integer,
    objetivos text,
    diabetes character varying(3),
    cardiopatia character varying(3),
    hipertensao character varying(3),
    outras_doencas character varying(3),
    doencas_cronicas_outros_descricao text,
    fumante character varying(3),
    bebidas_alcoolicas character varying(3),
    exercicio_regular character varying(3),
    exercicio_frequencia text,
    exercicio_tipo text,
    medicamentos character varying(3),
    medicamentos_descricao text,
    cirurgia character varying(3),
    cirurgia_descricao text,
    historico_diabetes character varying(3),
    historico_cardiopatia character varying(3),
    historico_hipertensao character varying(3),
    historico_cancer character varying(3),
    historico_outros character varying(3),
    historico_descricao text,
    problemas_osteoarticulares character varying(3),
    problemas_osteoarticulares_descricao text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.anamnese OWNER TO postgres;

--
-- Name: anamnese_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.anamnese_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.anamnese_id_seq OWNER TO postgres;

--
-- Name: anamnese_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.anamnese_id_seq OWNED BY public.anamnese.id;


--
-- Name: antropometria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.antropometria (
    id integer NOT NULL,
    aluno_id integer,
    peso numeric,
    estatura numeric,
    braco_relaxado_direito numeric,
    braco_relaxado_esquerdo numeric,
    torax numeric,
    braco_contraido_direito numeric,
    braco_contraido_esquerdo numeric,
    cintura numeric,
    antebraco_direito numeric,
    antebraco_esquerdo numeric,
    abdomem numeric,
    coxa_proximal_direita numeric,
    coxa_proximal_esquerda numeric,
    quadril numeric,
    perna_direita numeric,
    perna_esquerda numeric,
    subescapular numeric,
    triceps numeric,
    axilar_medial_vertical numeric,
    biceps numeric,
    supra_iliaca_anterior numeric,
    coxa_proximal numeric,
    supra_iliaca_medial numeric,
    coxa_medial numeric,
    peitoral numeric,
    perna numeric,
    abdominal_vertical numeric,
    biestiloide numeric,
    biependicondilar_umeral numeric,
    biependicondilar_femural numeric,
    imc numeric,
    icq numeric,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.antropometria OWNER TO postgres;

--
-- Name: antropometria_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.antropometria_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.antropometria_id_seq OWNER TO postgres;

--
-- Name: antropometria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.antropometria_id_seq OWNED BY public.antropometria.id;


--
-- Name: calculadora; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.calculadora (
    id integer NOT NULL,
    id_aluno integer NOT NULL,
    formula character varying(255) NOT NULL,
    data date NOT NULL,
    resultado_porcentagem numeric(5,2) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    massa_gorda numeric(5,2),
    massa_magra numeric(5,2)
);


ALTER TABLE public.calculadora OWNER TO postgres;

--
-- Name: calculadora_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.calculadora_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.calculadora_id_seq OWNER TO postgres;

--
-- Name: calculadora_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.calculadora_id_seq OWNED BY public.calculadora.id;


--
-- Name: testes_fisicos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.testes_fisicos (
    id integer NOT NULL,
    aluno_id integer,
    banco_de_wells numeric,
    distancia_percorrida numeric,
    fc_max numeric,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    vo2_maximo numeric(5,2)
);


ALTER TABLE public.testes_fisicos OWNER TO postgres;

--
-- Name: testes_fisicos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.testes_fisicos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.testes_fisicos_id_seq OWNER TO postgres;

--
-- Name: testes_fisicos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.testes_fisicos_id_seq OWNED BY public.testes_fisicos.id;


--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuarios (
    id integer NOT NULL,
    nome character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    senha character varying(255) NOT NULL,
    isadmin boolean NOT NULL,
    token character varying(255),
    data_expiracao timestamp without time zone
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuarios_id_seq OWNER TO postgres;

--
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- Name: alunos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.alunos ALTER COLUMN id SET DEFAULT nextval('public.alunos_id_seq'::regclass);


--
-- Name: anamnese id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.anamnese ALTER COLUMN id SET DEFAULT nextval('public.anamnese_id_seq'::regclass);


--
-- Name: antropometria id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.antropometria ALTER COLUMN id SET DEFAULT nextval('public.antropometria_id_seq'::regclass);


--
-- Name: calculadora id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.calculadora ALTER COLUMN id SET DEFAULT nextval('public.calculadora_id_seq'::regclass);


--
-- Name: testes_fisicos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.testes_fisicos ALTER COLUMN id SET DEFAULT nextval('public.testes_fisicos_id_seq'::regclass);


--
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- Data for Name: alunos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.alunos (id, nome, data_nascimento, telefone, sexo, email) FROM stdin;
1	Neymar da silva junior	2000-08-01	999999999	masculino	neymarjr@hotmail.com
2	Thais	1996-07-01	999999999	feminino	thias@hotmail.com
4	Arlison	1994-12-02	999999999	masculino	arlison@hotmail.com
\.


--
-- Data for Name: anamnese; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.anamnese (id, aluno_id, objetivos, diabetes, cardiopatia, hipertensao, outras_doencas, doencas_cronicas_outros_descricao, fumante, bebidas_alcoolicas, exercicio_regular, exercicio_frequencia, exercicio_tipo, medicamentos, medicamentos_descricao, cirurgia, cirurgia_descricao, historico_diabetes, historico_cardiopatia, historico_hipertensao, historico_cancer, historico_outros, historico_descricao, problemas_osteoarticulares, problemas_osteoarticulares_descricao, created_at) FROM stdin;
\.


--
-- Data for Name: antropometria; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.antropometria (id, aluno_id, peso, estatura, braco_relaxado_direito, braco_relaxado_esquerdo, torax, braco_contraido_direito, braco_contraido_esquerdo, cintura, antebraco_direito, antebraco_esquerdo, abdomem, coxa_proximal_direita, coxa_proximal_esquerda, quadril, perna_direita, perna_esquerda, subescapular, triceps, axilar_medial_vertical, biceps, supra_iliaca_anterior, coxa_proximal, supra_iliaca_medial, coxa_medial, peitoral, perna, abdominal_vertical, biestiloide, biependicondilar_umeral, biependicondilar_femural, imc, icq, created_at) FROM stdin;
1	1	75	179	30	31	100	33	32	80	26	28	91	40	41	96	28	28	18	14	10	8	21	30	22	31	16	12	36	1	1	1	23.41	0.83333333333333	2024-06-07 14:18:09.442735
2	2	75	172	1	1	1	1	1	1	1	1	1	1	1	1	1	1	25	22	16	12	35	42	30	45	18	10	26	1	1	1	25.35	1	2024-06-07 14:22:41.543055
4	4	85	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	25	16	0	30	0	0	0	\N	\N	2024-11-01 11:25:19.50511
\.


--
-- Data for Name: calculadora; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.calculadora (id, id_aluno, formula, data, resultado_porcentagem, created_at, massa_gorda, massa_magra) FROM stdin;
1	4	percentual_gordura_masculina	2024-11-01	12.31	2024-11-22 10:00:09.35713	74.54	85.00
2	4	percentual_gordura_masculina	2024-11-01	12.31	2024-11-22 10:09:21.626943	74.54	85.00
13	4	percentual_gordura_masculina	2024-11-01	12.42	2025-06-27 11:15:34.882104	10.56	74.44
14	2	percentual_gordura_feminina	2024-06-07	34.85	2025-06-27 11:16:45.744213	26.14	48.86
\.


--
-- Data for Name: testes_fisicos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.testes_fisicos (id, aluno_id, banco_de_wells, distancia_percorrida, fc_max, created_at, vo2_maximo) FROM stdin;
1	4	32	2500	199	2024-11-22 10:08:25.751904	55.70
\.


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuarios (id, nome, email, senha, isadmin, token, data_expiracao) FROM stdin;
1	Raphael Marques	admin@example.com	$2y$10$4p0OetXFJiP4aXOluLG/cu4InVeh23XmjZAypjKe1U9tC9mQheZVW	t	\N	\N
2	kevyn	kevyn.carlos@hotmail.com	$2y$10$iiwmtAdlvvjW5WRcRLWCD.6AmX0iO73NwcRMl4g4x08NEdOTxDoZe	f	\N	\N
\.


--
-- Name: alunos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.alunos_id_seq', 4, true);


--
-- Name: anamnese_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.anamnese_id_seq', 1, false);


--
-- Name: antropometria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.antropometria_id_seq', 4, true);


--
-- Name: calculadora_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.calculadora_id_seq', 14, true);


--
-- Name: testes_fisicos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.testes_fisicos_id_seq', 1, true);


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 2, true);


--
-- Name: alunos alunos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.alunos
    ADD CONSTRAINT alunos_pkey PRIMARY KEY (id);


--
-- Name: anamnese anamnese_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.anamnese
    ADD CONSTRAINT anamnese_pkey PRIMARY KEY (id);


--
-- Name: antropometria antropometria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.antropometria
    ADD CONSTRAINT antropometria_pkey PRIMARY KEY (id);


--
-- Name: calculadora calculadora_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.calculadora
    ADD CONSTRAINT calculadora_pkey PRIMARY KEY (id);


--
-- Name: testes_fisicos testes_fisicos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.testes_fisicos
    ADD CONSTRAINT testes_fisicos_pkey PRIMARY KEY (id);


--
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: anamnese anamnese_aluno_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.anamnese
    ADD CONSTRAINT anamnese_aluno_id_fkey FOREIGN KEY (aluno_id) REFERENCES public.alunos(id);


--
-- Name: antropometria antropometria_aluno_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.antropometria
    ADD CONSTRAINT antropometria_aluno_id_fkey FOREIGN KEY (aluno_id) REFERENCES public.alunos(id);


--
-- Name: testes_fisicos testes_fisicos_aluno_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.testes_fisicos
    ADD CONSTRAINT testes_fisicos_aluno_id_fkey FOREIGN KEY (aluno_id) REFERENCES public.alunos(id);


--
-- PostgreSQL database dump complete
--

