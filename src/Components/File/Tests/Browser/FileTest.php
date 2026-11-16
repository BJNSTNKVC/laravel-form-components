<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\File\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class FileTest extends TestCase
{
    /**
     * Files with column and floating titles, and an invalid file.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <x-form::file
                name="file_column_bottom"
                title="File"
                label-type="column"
                border="bottom"
                border-radius="squared"
            />

            <x-form::file
                name="file_floating_rounded_s"
                title="File"
                label-type="floating"
                border="full"
                border-radius="rounded-s"
            />

            <x-form::file
                name="file_error"
                title="File"
                invalidated-title="true"
            />
            BLADE;
    }

    /**
     * Validation errors shown on the page.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function errors(): array
    {
        return [
            'file_error' => 'The file field is required.',
        ];
    }

    #[Test]
    public function it_floats_the_title_once_a_file_is_chosen(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertRestingTitle($browser, 'file_floating_rounded_s');

            $browser->attach('file_floating_rounded_s', __FILE__);

            $this->assertRisenTitle($browser, 'file_floating_rounded_s');
        });
    }

    #[Test]
    public function it_shows_the_name_of_the_chosen_file(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->attach('file_column_bottom', __FILE__)
                ->assertValue('#file_column_bottom ~ [x-ref="holder"]', 'FileTest.php');
        });
    }

    #[Test]
    public function it_shows_the_number_of_chosen_files(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->choose($browser, 'file_column_bottom', ['one.txt', 'two.txt']);

            $browser->assertValue('#file_column_bottom ~ [x-ref="holder"]', '2 files');
        });
    }

    #[Test]
    public function it_hides_the_error_once_a_file_is_chosen(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->attach('file_error', __FILE__);

            $this->assertValid($browser, 'file_error');
            $this->assertValidTitle($browser, 'file_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_the_file_is_removed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->attach('file_error', __FILE__);

            $this->choose($browser, 'file_error', []);

            $this->assertInvalid($browser, 'file_error');
            $this->assertInvalidTitle($browser, 'file_error');
        });
    }

    /**
     * Choose files by name, as a user picking them would.
     *
     * @param  list<string>  $names
     */
    private function choose(Browser $browser, string $field, array $names): void
    {
        $browser->script(sprintf(
            'const input = document.getElementById(%s);
            const transfer = new DataTransfer();
            %s.forEach((name) => transfer.items.add(new File([""], name)));
            input.files = transfer.files;
            input.dispatchEvent(new Event("input", { bubbles: true }));',
            json_encode($field),
            json_encode($names),
        ));
    }
}
