<?php
namespace PhpWord\Tests\Writer;

use PhpOffice\PhpWord;
use PhpOffice\PhpWord\Writer\ODText;

/**
 * @package                     PhpWord\Tests
 * @coversDefaultClass          PhpOffice\PhpWord\Writer\ODText
 * @runTestsInSeparateProcesses
 */
class ODTextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test construct
     */
    public function testConstruct()
    {
        $object = new ODText(new PhpWord());

        $this->assertInstanceOf('PhpOffice\\PhpWord', $object->getPhpWord());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\HashTable', $object->getDrawingHashTable());

        $this->assertEquals('./', $object->getDiskCachingDirectory());
        foreach (array('Content', 'Manifest', 'Meta', 'Mimetype', 'Styles') as $part) {
            $this->assertInstanceOf(
                "PhpOffice\\PhpWord\\Writer\\ODText\\{$part}",
                $object->getWriterPart($part)
            );
            $this->assertInstanceOf(
                'PhpOffice\\PhpWord\\Writer\\ODText',
                $object->getWriterPart($part)->getParentWriter()
            );
        }
    }

    /**
     * @covers                    ::getPhpWord
     * @expectedException         Exception
     * @expectedExceptionMessage  No PhpWord assigned.
     */
    public function testConstructWithNull()
    {
        $object = new ODText();
        $object->getPhpWord();
    }

    /**
     * @covers ::save
     */
    public function testSave()
    {
        $imageSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'PhpWord.png')
        );
        $objectSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $file = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'temp.odt')
        );

        $phpWord = new PhpWord();
        $phpWord->addFontStyle('Font', array('size' => 11));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->createSection();
        $section->addText('Test 1', 'Font');
        $section->addTextBreak();
        $section->addText('Test 2', null, 'Paragraph');
        $section->addLink('http://test.com');
        $section->addTitle('Test', 1);
        $section->addPageBreak();
        $section->addTable();
        $section->addListItem('Test');
        $section->addImage($imageSrc);
        $section->addObject($objectSrc);
        $section->addTOC();
        $section = $phpWord->createSection();
        $textrun = $section->createTextRun();
        $textrun->addText('Test 3');
        $writer = new ODText($phpWord);
        $writer->save($file);

        $this->assertTrue(file_exists($file));

        unlink($file);
    }

    /**
     * @covers ::save
     * @todo   Haven't got any method to test this
     */
    public function testSavePhpOutput()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->createSection();
        $section->addText('Test');
        $writer = new ODText($phpWord);
        $writer->save('php://output');
    }

    /**
     * @covers                   ::save
     * @expectedException        Exception
     * @expectedExceptionMessage PhpWord object unassigned.
     */
    public function testSaveException()
    {
        $writer = new ODText();
        $writer->save();
    }

    /**
     * @covers ::getWriterPart
     */
    public function testGetWriterPartNull()
    {
        $object = new ODText();
        $this->assertNull($object->getWriterPart('foo'));
    }

    /**
     * @covers ::setUseDiskCaching
     * @covers ::getUseDiskCaching
     */
    public function testSetGetUseDiskCaching()
    {
        $object = new ODText();
        $object->setUseDiskCaching(true, PHPWORD_TESTS_DIR_ROOT);
        $this->assertTrue($object->getUseDiskCaching());
        $this->assertEquals(PHPWORD_TESTS_DIR_ROOT, $object->getDiskCachingDirectory());
    }

    /**
     * @covers            ::setUseDiskCaching
     * @expectedException Exception
     */
    public function testSetUseDiskCachingException()
    {
        $dir = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, 'foo')
        );

        $object = new ODText($phpWord);
        $object->setUseDiskCaching(true, $dir);
    }
}