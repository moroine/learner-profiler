<?php

namespace Pfe\Bundle\BuilderBundle\Builder;

use Pfe\Bundle\CoreBundle\Entity\Module;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Description of ThemeBuilder
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class ModuleBuilder extends AbstractMoocBuilder
{

    protected static $BUILDER_NAME = "Module";

    public function build(OutputInterface $output)
    {
        $this->buildSection($output);
        $this->buildCategory($output);
    }

    public function buildSection(OutputInterface $output)
    {
        $this->stats['complete'] = 0;
        $this->stats['uncomplete'] = 0;
        $criteria = array('section' => null);
        $max = $this->countEntityBatches($criteria);
        if (!$max) {
            $output->writeln("<info>>>>Modules<<< All Sections are built</info>");
            return;
        }
        $output->writeln("<info>>>>Modules<<< Building Sections</info>");
        $progress = new ProgressBar($output, $max);
        $progress->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:3s%/%estimated:-3s% %memory:6s%');
        $progress->start();

        /* @var $modules Module[] */
        while ($modules = $this->getEntityBatch($criteria, $this->stats['uncomplete'])) {
            foreach ($modules as $key => $module) {
                $section = $this->doctrine->getRepository("PfeCoreBundle:Section")->findOneBy(array("mooc_id" => $module->getSectionMoocId()));
                if ($section) {
                    $module->setSection($section);
                    $this->doctrine->merge($module);
                    $this->stats['complete'] ++;
                } else {
                    $this->stats['uncomplete'] ++;
                }
                $progress->advance();
            }
            $this->flushEntities();
        }

        $progress->finish();
        $this->outputStats($output);
    }

    public function buildCategory(OutputInterface $output)
    {
        $this->stats['complete'] = 0;
        $this->stats['uncomplete'] = 0;
        $criteria = array('category' => null);
        $max = $this->countEntityBatches($criteria);
        if (!$max) {
            $output->writeln("<info>>>>Modules<<< All Categories are built</info>");
            return;
        }
        $output->writeln("<info>>>>Modules<<< Building Categories</info>");
        $progress = new ProgressBar($output, $max);
        $progress->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:3s%/%estimated:-3s% %memory:6s%');
        $progress->start();

        /* @var $modules Module[] */
        while ($modules = $this->getEntityBatch($criteria, $this->stats['uncomplete'])) {
            foreach ($modules as $key => $module) {
                switch ($module->getType()) {
                    case "feedback";
                    case "forum";
                        $category = "community";
                        break;
                    case "glossary";
                    case "page";
                    case "resource";
                    case "url";
                        $category = "theroy";
                        break;
                    case "quiz";
                        $category = "examination";
                        break;
                    case "workshop";
                        $category = "practice";
                        break;
                    default:
                        $category = null;
                }
                if ($category !== null) {
                    $module->setCategory($category);
                    $this->doctrine->merge($module);
                    $this->stats['complete'] ++;
                } else {
                    $this->stats['uncomplete'] ++;
                }
                $progress->advance();
            }
            $this->flushEntities();
        }

        $progress->finish();
        $this->outputStats($output);
    }

    protected function getRepository()
    {
        return $this->doctrine->getRepository("PfeCoreBundle:Module");
    }

}
