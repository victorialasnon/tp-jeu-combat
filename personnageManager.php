<?php
include ('../config/db.php');
class CharacterManager
{
  private $_db; // Instance de PDO
  
  public function __construct($db)
  {
    $this->setDb($db);
  }
  
  public function add(Character $perso)
  {
    $q = $this->_db->prepare('INSERT INTO charactersName VALUES(:nom)');
    $q->bindValue(':names', $perso->nom());
    $q->execute();
    
    $perso->hydrate([
      'id' => $this->_db->lastInsertId(),
      'damage' => 0,
    ]);
  }
  
  public function count()
  {
    return $this->_db->query('SELECT COUNT(*) FROM characters')->fetchColumn();
  }
  
  public function delete(Character $perso)
  {
    $this->_db->exec('DELETE FROM characters WHERE id = '.$perso->id());
  }
  
  public function exists($info)
  {
    if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe.
    {
      return (bool) $this->_db->query('SELECT COUNT(*) FROM characters WHERE id = '.$info)->fetchColumn();
    }
    
    // Sinon, c'est qu'on veut vérifier que le nom existe ou pas.
    
    $q = $this->_db->prepare('SELECT COUNT(*) FROM characters WHERE name = :name');
    $q->execute([':name' => $info]);
    
    return (bool) $q->fetchColumn();
  }
  
  public function get($info)
  {
    if (is_int($info))
    {
      $q = $this->_db->query('SELECT id, name, damage FROM characters WHERE id = '.$info);
      $donnees = $q->fetch(PDO::FETCH_ASSOC);
      
      return new Character($donnees);
    }
    else
    {
      $q = $this->_db->prepare('SELECT id, name, damage FROM characters WHERE name = :name');
      $q->execute([':name' => $info]);
    
      return new Character($q->fetch(PDO::FETCH_ASSOC));
    }
  }
  
  public function getList($name)
  {
    $persos = [];
    
    $q = $this->_db->prepare('SELECT id, name, damage FROM characters WHERE name <> :name ORDER BY name');
    $q->execute([':name' => $name]);
    
    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
      $persos[] = new Character($donnees);
    }
    
    return $persos;
  }
  
  public function update(Character $perso)
  {
    $q = $this->_db->prepare('UPDATE characters SET damage = :damage WHERE id = :id');
    
    $q->bindValue(':damage', $perso->damage(), PDO::PARAM_INT);
    $q->bindValue(':id', $perso->id(), PDO::PARAM_INT);
    
    $q->execute();
  }
  
  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }
}