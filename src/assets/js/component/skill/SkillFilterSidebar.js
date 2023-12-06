import {Card, CardContent} from "@mui/material";
import {FilterList, FilterListItem, FilterLiveSearch, SavedQueriesList} from "react-admin";

export const SkillFilterSidebar = () => {
    return <Card sx={{ order: -1, mr: 2, mt: 6, width: '10%' }}>
        <CardContent>
            {/*<SavedQueriesList/>*/}
            {/*<FilterLiveSearch  name={'live_search'}/>*/}
            <FilterList label="Compétence" icon={''}>
                <FilterListItem label="Trait" value={{ type: 'Trait', category: '' }} />
                <FilterListItem label="Général" value={{ type: 'Compétence', category: 'Général' }} />
                <FilterListItem label="Agilité" value={{ type: 'Compétence', category: 'Agilité' }} />
                <FilterListItem label="Force" value={{ type: 'Compétence', category: 'Force' }} />
                <FilterListItem label="Mutation" value={{ type: 'Compétence', category: 'Mutation' }} />
                <FilterListItem label="Passe" value={{ type: 'Compétence', category: 'Passe' }} />
            </FilterList>
        </CardContent>
    </Card>;
}