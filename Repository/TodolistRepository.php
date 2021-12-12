<?php

namespace Repository {

    use Entity\Todolist;

    interface TodolistRepository
    {
        function save(Todolist $todolist): void;

        function remove(int $number): bool;

        function findAll(): array;
    }

    class TodolistRepositoryImpl implements TodolistRepository
    {

        public function __construct(private \PDO $connection)
        {
        }

        public array $todolist = array();

        function save(Todolist $todolist): void
        {
            $sql = "INSERT INTO todolist(todo) VALUES(?)";
            $result = $this->connection->prepare($sql);
            $result->execute([$todolist->getTodo()]);
        }

        function remove(int $number): bool
        {
            $sql = "SELECT * FROM todolist WHERE id = ?";
            $result = $this->connection->prepare($sql);
            $result->execute([$number]);

            if ($result->fetch()){
                $sql = "DELETE FROM todolist WHERE id = ?";
                $result = $this->connection->prepare($sql);
                $result->execute([$number]);
                return true;
            }else{
                return false;
            }
        }

        function findAll(): array
        {
            $sql = "SELECT * FROM todolist";
            $result = $this->connection->query($sql);

            $array = [];

            foreach ($result as $row){
                $todolist = new Todolist();
                $todolist->setId($row["id"]);
                $todolist->setTodo($row["todo"]);

                $array[] = $todolist;
            }

            return $array;
        }
    }

}
