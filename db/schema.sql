create extension if not exists pgcrypto;

create type donationMethod as enum (
	'cash',
	'check',
	'online'
);

create table users (
	id integer generated always as identity primary key,
	name text not null,
	email text unique not null,
	pswhash text not null,
	created_at timestamp default current_timestamp
);

create table volunteers (
	id integer primary key references users (id),
	skills text[],
	availability date[]
);

create table donors (
	id integer primary key references users (id),
	donationMethod donationMethod
);

create table beneficiaries (
	id integer primary key references users (id),
	needs text[]
);

create table assignments (
	id integer primary key references users (id),
	volunteer_id integer references volunteers (id)
);

create table events (
	id integer generated always as identity primary key,
	name text,
	date date,
	address text
);
