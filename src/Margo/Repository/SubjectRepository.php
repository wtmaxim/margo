<?php

namespace Margo\Repository;

use Doctrine\DBAL\Connection;
use Margo\Entity\Subject;

class SubjectRepository implements RepositoryInterface
{

    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Saves the category to the database.
     *
     * @param \Margo\Entity\Category $category
     */
    public function save($subject)
    {
        $subjectData = array(
            'id' => $subject->getIdSubject(),
            'name' => $subject->getNameSubject(),
            'timeVolume' => $subject->getTimeVolume(),
            'coefficient' => $subject->getCoefficient()
        );

        if ($subject->getIdSubject()) {

            $this->db->update('subject', $subjectData, array('id' => $subject->getIdSubject()));
        }
        else {
            $this->db->insert('subject', $subjectData);
            $id = $this->db->lastInsertId();
            $subject->setIdSubject($id);
        }
    }

    /**
     * Deletes category.
     *
     * @param \Margo\Entity\Category $category
     */
    public function delete($subject)
    {
        return $this->db->delete('subject', array('id' => $subject->getIdSubject()));
    }

    /**
     * Returns the total number of category.
     *
     * @return integer The total number of category.
     */
    public function getCount() {
        return $this->db->fetchColumn('SELECT COUNT(id) FROM subject');
    }

    /**
     * Returns an category matching the supplied id.
     *
     * @param integer $id
     *
     * @return \Margo\Entity\Category|false An entity object if found, false otherwise.
     */
    public function find($id)
    {
        $subjectData = $this->db->fetchAssoc('SELECT * FROM subject WHERE id = ?', array($id));
        return $subjectData ? $this->buildSubject($subjectData) : FALSE;
    }

    /**
     * Returns a collection of category, sorted by name.
     *
     * @param integer $limit
     *   The number of $category to return.
     * @param integer $offset
     *   The number of $category to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of $category, keyed by $category id.
     */
    public function findAll($limit, $offset = 0, $orderBy = array())
    {
        // Provide a default orderBy.
        if (!$orderBy) {
            $orderBy = array('name' => 'ASC');
        }

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('e.*')
            ->from('subject', 'e')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy(key($orderBy), current($orderBy));
        $statement = $queryBuilder->execute();
        $subjectsData = $statement->fetchAll();
        $subject = array();
        foreach ($subjectsData as $subjectData) {
            $subjectId = $subjectData['id'];
            $subjects[$subjectId] = $this->buildSubject($subjectData);
        }
        return $subjects;
    }
    /**
     * Instantiates an $subject entity and sets its properties using db data.
     *
     * @param array $subjectData
     *   The array of db data.
     *
     * @return \Margo\Entity\Subject
     */
    protected function buildSubject($subjectData)
    {
        $subject = new Subject();
        $subject->setIdSubject($subjectData['id']);
        $subject->setNameSubject($subjectData['name']);
        $subject->setIdCategory($subjectData['idCategory']);
        $subject->setTimeVolume($subjectData['timeVolume']);
        $subject->setCoefficient($subjectData['coefficient']);
        return $subject;
    }

}