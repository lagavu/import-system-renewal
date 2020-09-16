# Тестовое задание

## Правила оформления результата

Результат выполнения данного тестового задания должен быть представлен в виде репозитория 
на [GitHub](http://github.com) с сохранением истории коммитов.

В корень проекта необходимо положить `docker-compose.yml` файл для запуска проекта.

## Используемые технологии

* Symfony 3.x

* Doctrine Migrations для изменения схемы базы данных

* PHPUnit для юнит-тестирования

## Пожелания к реализации

Будет плюсом, если смоделированная предметная область будет покрыта юнит-тестами, отвязанными от инфраструктурного слоя и слоя представления

## Описание задачи

Необходимо реализовать систему импорта данных от дистрибьюторов продукции компании по 
аптечным точкам и возможность посмотреть, сколько в каждую из аптек пришло товара суммарно по всем дистрибьюторам аптеки. 

Дистрибьюторы предоставляют информацию о том, в какие аптечные 
точки они развезли нашу продукцию. У каждого дистрибьютора наименование наших
препаратов а также обозначение аптеки своё. 

Файлы для импорта подготавливаются дистрибьюторами и представляют собой текстовые файлы
со строками, включающами в себя следующую информацию: аптечная точка, препарат, количество.
По одной аптеке и препарату, записи могут встречаться несколько раз.

Примеры файлов: [Дистрибьютор 1](distributor2.txt), [Дистрибьютор 2](distributor2.txt)

## Что будет делать пользователь системы

* Выбирая в списке дистрибьютора, пользователь может загрузить в систему файл данных по нему.

* Все ненайденные привязки к системным препаратам и аптекам должны быть предоставлены пользователю в виде формы редактирования привязок, где он сможет настроить соответствие между наименованиями в файле и в системе, после чего импортирует пропущенные записи

* Выбирая в списке аптеку, пользователь может посмотреть, сколько в аптеку пришло товара со всех дистрибьюторов и с каждого в отдельности

* Пользователь должен иметь возможность редактировать соответствия между наименованиями, используемыми в файле данных и в системе (аптеки, препараты)

