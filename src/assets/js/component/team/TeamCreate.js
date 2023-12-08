import {AutocompleteInput, Create, ReferenceInput, SelectInput, SimpleForm, TextInput} from "react-admin";

export const TeamCreate = props => (
    <Create {...props}>
        <SimpleForm>
            <ReferenceInput source={"faction"} reference={'factions'} name={'faction'}>
                <AutocompleteInput optionText="name" name={'faction_name'}/>
            </ReferenceInput>
            <TextInput name="name" source="name"/>
            <SelectInput name="playType" source="playType" choices={[
                { id: 'league', name: 'Ligue' },
                { id: 'exhibition', name: 'Exhibition' },
            ]} />
            <SelectInput name="playCategory" source="playCategory" choices={[
                { id: 'elevens', name: '11s' },
                { id: 'sevens', name: '7s' },
            ]} />
        </SimpleForm>
    </Create>
);