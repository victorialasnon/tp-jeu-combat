<?php
class Character
{
  private $_damage,
          $_id,
          $_name;
  
  const HIT_MYSELF = 1;
  const CHARACTER_KILLED = 2; 
  const CHARACTER_HIT = 3; 
  
  
  public function __construct(array $donnees)
  {
    $this->hydrate($donnees);
  }
  
  public function hit(Personnage $perso)
  {
    if ($perso->id() == $this->_id)
    {
      return self::CEST_MOI;
    }
    
    // On indique au personnage qu'il doit recevoir des dégâts.
    // Puis on retourne la valeur renvoyée par la méthode : self::CHARACTER_KILLED ou self::CHARACTER_HIT
    return $perso->takeDamage();
  }
  
  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value)
    {
      $method = 'set'.ucfirst($key);
      
      if (method_exists($this, $method))
      {
        $this->$method($value);
      }
    }
  }
  
  public function takeDamage()
  {
    $this->_damage += 5;
    
    // Si on a 100 de dégâts ou plus, on dit que le personnage a été tué.
    if ($this->_degats >= 100)
    {
      return self::CHARACTER_KILLED;
    }
    
    // Sinon, on se contente de dire que le personnage a bien été frappé.
    return self::CHARACTER_HIT;
  }
  
  
  // GETTERS //
  

  public function damage()
  {
    return $this->_damage;
  }
  
  public function id()
  {
    return $this->_id;
  }
  
  public function name()
  {
    return $this->_name;
  }
  
  public function setDamages($damage)
  {
    $damage = (int) $damage;
    
    if ($damage >= 0 && $damage <= 100)
    {
      $this->_damage = $damage;
    }
  }
  
  public function setName($name)
  {
    if (is_string($name))
    {
      $this->_name = $name;
    }
  }
}