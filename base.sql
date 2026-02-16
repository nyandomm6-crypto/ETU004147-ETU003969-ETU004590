create database takalo;
use takalo;

create table users (
    id serial primary key,
    nom varchar(255) not null unique,
    email varchar(255) not null unique,
    password varchar(255) not null,
    type enum('admin', 'user') not null default 'user'
);
create table objet(
    id serial primary key,
    nom varchar(255) not null,
    description text,
    prix decimal(10, 2) not null,
    etat decimal(10, 2) not null,
    id_categorie integer not null,
    id_user integer not null

);
create table categories (
    id serial primary key,
    nom varchar(255) not null unique
);
create table photo_objet (
    id serial primary key,
    path varchar(255) not null,
    id_objet integer not null
);
create table photo_categorie (
    id serial primary key,
    path varchar(255) not null,
    id_categorie integer not null
);
create table demande (
    id serial primary key,
    id_sender integer not null,
    id_objet integer not null,
    etat enum('en_attente', 'acceptee', 'refusee') not null default 'en_attente'
);
create table histo_appartenance (
    id serial primary key,
    id_user integer not null,
    id_objet integer not null,
    date_acquisition timestamp not null
);