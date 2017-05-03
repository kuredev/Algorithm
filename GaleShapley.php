<?php

namespace Kuredev\GaleShapley;

/**
 *
 * @author kure
 *         Usage
 *
 */
class GaleShapley {
	private $menArray = array ();
	private $womenArray = array ();
	private $matches;

	/**
	 *
	 * @param array $menArray
	 * @param array $womenArray
	 */
	public function __construct(array $menArray, array $womenArray) {
		$this->menArray = $menArray;
		$this->womenArray = $womenArray;
		$this->matches = new Matches ();
	}

	private function isFreeMan(array $menArray) {
		$flag = null;
		foreach ( $menArray as $man ) {
			if ($man->isMarried ()) {
			}else{
				return true;
			}
		}
		return false;
	}

	private function getWomanByName(string $name){
		foreach ($this->womenArray as $woman){
			if($woman->getName() === $name){
				return $woman;
			}
		}
	}

	private function getManByName(string $name){
		foreach ($this->menArray as $man){
			if($man->getName() === $name){
				return $man;
			}
		}
	}

	private function getFreeMan(array $memArray) {
		foreach ( $memArray as $man ) {
			if (! $man->isMarried ()) {
				return $man;
			}
		}
	}

	public function calc() {
		while ( $this->isFreeMan ( $this->menArray ) ) {
			// foreach ( $this->menArray as $m ) {
			$m = $this->getFreeMan ( $this->menArray );
			// mの選好リストの先頭の女性をwとする
			$w = $this->getWomanByName($m->getMostPreferPerson ());
			// wがフリー
			if (! $w->isMarried ()) {
				$this->matches->addMatch ( new Match ( $m, $w ) );
			} else {
				// wの婚約相手をm'とする
				$marriedManName = $w->getMarriedMan ();
				$m_ = $this->getManByName($marriedManName);
				// wはmよりm'が好き
				if ($w->isMorePrefered ( $m_, $m )) {
					// mの選好リストからwを削除する
					$m->deletePrederdWoman ( $w->getName () );
				} else {
					// Mから(m',w)を削除し、(m,w)を加える
					$this->matches->deleteMatch ( $m_->getName (), $w->getName () );
					$this->matches->addMatch ( new Match ( $m, $w ) );
					$m_->deletePreferPerson ( $w->getName () );
				}
			}
		}
//		var_dump($this->matches);
		return $this->matches;
	}
}


class Match {
	private $man, $woman;
	public function __construct(Man $man, Woman $woman) {
		$this->man = $man;
		$this->woman = $woman;
	}
	public function getWoman() {
		return $this->woman;
	}
	public function getMan() {
		return $this->man;
	}
	public function getWomanName() {
		return $this->woman->getName ();
	}
	public function getManName() {
		return $this->man->getName ();
	}
}

class Matches {
	private $matchArr = array ();
	public function addMatch(Match $m) {
		array_push ( $this->matchArr, $m );
		$m->getMan ()->marry ( $m->getWoman ()->getName () );
		$m->getWoman ()->marry ( $m->getMan ()->getName () );
	}
	public function deleteMatch(string $manName, string $womanName) {
		foreach ( $this->matchArr as $key => $match ) {
			if ($match->getManName () === $manName) {
				if ($match->getWomanName () === $womanName) {
					unset ( $this->matchArr [$key] );
					$match->getWoman ()->unMarry ();
					$match->getMan ()->unMarry ();
				}
			}
		}
	}
}

class Man extends Person {
	public function getMarriedWoman() {
		return $this->marriedName;
	}
	public function deletePrederdWoman(string $womanName) {
		if (($key = array_search ( $womanName, $this->preferArray )) !== false) {
			unset ( $this->preferArray [$key] );
		}
	}
}

class Woman extends Person {
	public function getMarriedMan() {
		return $this->marriedName;
	}
}

class Person {
	protected  $name;
	protected  $preferArray;
	protected  $marriedName;

	/**
	 * p1がp2より好きか
	 *
	 * @param unknown $p1
	 * @param unknown $p2
	 */
	public function isMorePrefered($p1, $p2) {
		$p1num = array_keys ( $this->preferArray, $p1->getName () ) [0];
		$p2num = array_keys ( $this->preferArray, $p2->getName () ) [0];
		if ($p1num < $p2num) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 *
	 * @param string $name
	 * @param array[string] $preferArray
	 */
	public function __construct($name, $preferArray) {
		$this->name = $name;
		$this->preferArray = $preferArray;
	}
	public function getMostPreferPerson() {
		return array_values ( $this->preferArray ) [0];
	}
	public function isMarried() {
		if ($this->marriedName == null) {
			return false;
		} else {
			return true;
		}
	}
	public function getName() {
		return $this->name;
	}

	public function deletePreferPerson(string $name) {
		if (($key = array_search ( $name, $this->preferArray )) !== false) {
			unset ( $this->preferArray [$key] );
		}
	}
	public function marry($name) {
		$this->marriedName = $name;
	}
	public function unMarry() {
		$this->marriedName = null;
	}
}

