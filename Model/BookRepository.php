<?php

namespace Model;

use Library\PdoAwareTrait;
use Model\Entity\Book;

class BookRepository
{
    use PdoAwareTrait;
    
    public function findAllActive($offset, $count)
    {
        $collection = [];
        $sth = $this->pdo->query("SELECT * FROM book WHERE status = 1 LIMIT {$offset}, {$count}");
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)) {
            $book = (new Book())
                ->setId($res['id'])
                ->setTitle($res['title'])
                ->setPrice($res['price'])
                ->setStatus((bool) $res['status'])
                ->setDescription($res['description'])
                ->setStyle($res['style_id'])
            ;
            
            $collection[] = $book;
        }
        
        return $collection;
    }
    
    public function findAll($offset, $count)
    {
        $collection = [];
        $sth = $this->pdo->query("SELECT * FROM book LIMIT {$offset}, {$count}");
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)) {
            $book = (new Book())
                ->setId($res['id'])
                ->setTitle($res['title'])
                ->setPrice($res['price'])
                ->setStatus((bool) $res['status'])
                ->setDescription($res['description'])
                ->setStyle($res['style_id'])
            ;
            
            $collection[] = $book;
        }
        
        return $collection;
    }
    
    public function findByIds(array $ids)
    {
        if (!$ids) {
            return [];
        }
        
        $placeholders = $collection = [];
        
        foreach ($ids as $id) {
            $placeholders[] = '?';
        }
        $placeholders = implode(',', $placeholders);
    
        $sth = $this->pdo->prepare("SELECT * FROM book WHERE id IN ($placeholders)");
        $sth->execute($ids);
        
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)) {
            $book = (new Book())
                ->setId($res['id'])
                ->setTitle($res['title'])
                ->setPrice($res['price'])
                ->setStatus((bool) $res['status'])
                ->setDescription($res['description'])
                ->setStyle($res['style_id'])
            ;
            
            $collection[] = $book;
        }
        
        return $collection;
    }
    
    public function findAllHydrateArray($offset = null, $count = null)
    {
        if (is_null($count) && !is_null($offset)) {
            throw new \Exception('Invalid arguments');
        }
        
        $offset = (int) $offset;
        
        $sql = 'SELECT * FROM book';
        
        if (!is_null($count)) {
            $count = (int) $count;
            $sql .= " LIMIT {$offset}, {$count}";
        }
        
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function findByIdHydrateArray($id)
    {
        $sth = $this->pdo->prepare("SELECT * FROM book WHERE id = :id");
        $sth->execute(['id' => $id]);
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function count()
    {
        $sth = $this->pdo->query('SELECT COUNT(*) AS count FROM book WHERE status = 1');
        return $sth->fetchColumn();
    }
}