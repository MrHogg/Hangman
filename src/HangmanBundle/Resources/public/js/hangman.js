function updateWord(word) {
    $('.word-wrapper').children('span').each(function (index, span) {
        $(span).text(word.charAt(index));
    });
}

function updateImage(imageNumber) {
    var image = $('#game-container').children('img');

    image.attr('src', image.attr('src').replace(/[0-7]/g, imageNumber));
}

function addMessage(type, message) {
    $('.flash-messages').append('<div class="type-' + type + '">' + message + '</div>');

    delayFade('.flash-messages div');
}

function delayFade(selector) {
    $(selector).delay(10000).fadeOut("slow", function () {
        $(this).remove();
    });
}

$(document).ready(function () {
    $('.letter-wrapper a').click(function (event) {
        event.preventDefault();

        var letter = $(this).parent('span');

        $.post(
            $(this).attr('href'),
            {},
            function (response) {
                if (response.error) {
                    addMessage('error', response.error);

                    return;
                }

                if (response.won) {
                    $('.game-won .attempts').text(response.guesses.invalid.length);
                    $('.game-won').removeClass('hidden');
                }

                if (response.lost) {
                    $('.game-lost .answer').text(response.answer);
                    $('.game-lost').removeClass('hidden');
                }

                if (response.won || response.lost) {
                    $('.letter-wrapper').addClass('hidden');
                    $('.reset-link').text('Start again');
                }

                letter.text(letter.children().text());

                updateWord(response.word);
                updateImage(response.guesses.invalid.length);
            },
            'json'
        );

        return false;
    });

    delayFade('.flash-messages div');
});
