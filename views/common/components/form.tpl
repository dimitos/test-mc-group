<form class="form {$class}">
    <div class="input__fields">
        {if $email == "true"}
        <div class="input__fields-item">
            <label class="input__field input__field-email">
                <input class="input input-default input-email" type="text" name="email" placeholder="Введите Ваш Email">
            </label>
        </div>
        {/if}
        {if $phone == "true" }
        <div class="input__fields-item">
            <label class="input__field input__field-phone">
                <input class="input input-default input-phone" type="tel" name="phone" placeholder="+7(___) --- -- --">
            </label>
        </div>
        {/if}
        {if $name == "true"}
        <div class="input__fields-item">
            <label class="input__field input__field-name">
                <input class="input input-default input-name" type="text" placeholder="Алюминиевый сплав">
            </label>
        </div>
        {/if}
    </div>
    {if $privacy == "true"}
        <div class="input__check">
            <label class="input__privacy">
                <input class="input__checkbox input__checkbox-privacy" type="checkbox">
                <span class="input__agreement"></span>
            </label>
            <p class="input__check-text">
                Согласие на обработку персональных данных
            </p>
        </div>
    {/if}
    {if $btn == "true"}
        <button class="btn btn-default form-btn {$btnClass}" type="button">{$btnText}</button>
    {/if}
</form>
